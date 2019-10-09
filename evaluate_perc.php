<?php

/*
    Questa funzione aggiorna un singolo indicatore. Deve essere chiamata dopo
    inserimento/rimozione di una nuova voce dell'indicatore nel form apposito
*/
function evaluate_perc_in($client, $indicatore, $data, $final)
{
    // Query data indicatore
    $query_minmax_in =('
        MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"A"})
        WITH max(date(f.data)) as DataEndPoint, min(date(f.data)) as DataStartPoint,
        min(f.valoreAtteso) as valoreAttesoIniziale, max(f.valoreAtteso) as valoreAttesoFinale
        RETURN
        valoreAttesoIniziale, DataStartPoint, valoreAttesoFinale, DataEndPoint
    ');
    $query_minmax_in = sprintf($query_minmax_in, $indicatore, $indicatore);

    // Determino estremi valore atteso
    $valore_atteso_iniziale = null; 
    $data_start_point = null;
    $valore_atteso_finale = null;
    $data_end_point = null;
    $result = $client->run($query_minmax_in);
    foreach ($result->records() as $record)
    {
        $valore_atteso_iniziale = $record->get("valoreAttesoIniziale");
        $data_start_point = $record->get("DataStartPoint");
        $valore_atteso_finale = $record->get("valoreAttesoFinale");
        $data_end_point = $record->get("DataEndPoint");
    }

    // Imposta valore raggiunto iniziale
    $valore_ragg_iniziale = $valore_atteso_iniziale;

    // Controllo date
    if ($data < $data_start_point)
        return 0;
    if ($data > $data_end_point)
        $data = $data_end_point;
    
    // calcolare Valore Atteso
    $valore_atteso = null;
    $valore_atteso_sup = null;

    $query_check_insterted = ('
        MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"A"})
        WHERE date(f.data) = date("%s")
        RETURN f.valoreAtteso as valoreAtteso
    ');
    $query_check_insterted = sprintf($query_check_insterted, $indicatore, $data);
    $result = $client->run($query_check_insterted);
    $record =$result->getRecord();
    if ($record != null)
    {
        $valore_atteso = $record->get("valoreAtteso");
        $valore_atteso_sup = $valore_atteso;
    }

    // Se arrivo a questo step la data e' compresa tra due valori attesi registrati, pertanto e' necessaria l'interpolazione
    // tra il valore atteso immediatamente precedente e successivo
    $data_atteso_inf = 0;
    $data_atteso_sup = 0;
    $valore_atteso_inf = 0;
    $valore_atteso_sup = 0;
    if ($valore_atteso == null)
    {
        // Trovo la data e il valore atteso precedente alla data di ricerca
        $prec_query = ('
            MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"A"})
            WHERE date(f.data) < date("%s")
            RETURN max(date(f.data)) as dataInferiore, max(f.valoreAtteso) as valoreAttesoInferiore
        ');
        $prec_query = sprintf($prec_query, $indicatore, $data);
        $result = $client->run($prec_query);
        foreach ($result->records() as $record)
        {
            $data_atteso_inf = new DateTime($record->get("dataInferiore"));
            $valore_atteso_inf = $record->get("valoreAttesoInferiore");
        }

        // Trovo la data e il valore atteso successivo alla data di ricerca
        $succ_query = ('
            MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"A"})
            WHERE date(f.data) > date("%s")
            RETURN min(date(f.data)) as dataSuperiore, min(f.valoreAtteso) as valoreAttesoSuperiore
        ');
        $succ_query = sprintf($succ_query, $indicatore, $data);
        $result = $client->run($succ_query);
        foreach ($result->records() as $record)
        {
            $data_atteso_sup = new DateTime($record->get("dataSuperiore"));
            // if ($data_atteso_sup == null) $data_atteso_sup = 0;
            $valore_atteso_sup = $record->get("valoreAttesoSuperiore");
            // if ($valore_atteso_sup == null) $valore_atteso_sup = 0;
        }

        $data1 = new DateTime($data);
        $valore_atteso = ($data1->diff($data_atteso_inf)->days / $data_atteso_sup->diff($data_atteso_inf)->days) * ($valore_atteso_sup - $valore_atteso_inf) + $valore_atteso_inf;
    }

    // calcolare Valore Raggiunto
    $valore_raggiunto = null;

    $query_check_insterted = ('
        MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"R"})
        WHERE date(f.data) = date("%s")
        RETURN f.valoreRaggiunto as valoreRaggiunto
    ');
    $query_check_insterted = sprintf($query_check_insterted, $indicatore, $data);
    $result = $client->run($query_check_insterted);
    $record = $result->getRecord();
    if ($record != null)
        $valore_raggiunto = $record->get("valoreRaggiunto");
        
    $data_raggiunto_inf = null;
    $data_raggiunto_sup = null;
    $valore_raggiunto_inf = null;
    $valore_raggiunto_sup = null;
    if ($valore_raggiunto == null)
    {
        // Trovo la data e il valore raggiunto precedente alla data di ricerca
        $prec_query = ('
            MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"R"})
            WHERE date(f.data) < date("%s")
            RETURN max(date(f.data)) as dataInferiore, max(f.valoreRaggiunto) as valoreRaggiuntoInferiore
        ');
        $prec_query = sprintf($prec_query, $indicatore, $data);
        $result = $client->run($prec_query);
        $record = $result->getRecord();
        if ($record != null)
        {
            $data_raggiunto_inf = new DateTime($record->get("dataInferiore"));
            $valore_raggiunto_inf = $record->get("valoreRaggiuntoInferiore");
        }

        // Trovo la data e il valore raggiunto successivo alla data di ricerca
        $succ_query = ('
            MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"R"})
            WHERE date(f.data) > date("%s")
            RETURN min(date(f.data)) as dataSuperiore, min(f.valoreRaggiunto) as valoreRaggiuntoSuperiore
        ');
        $succ_query = sprintf($succ_query, $indicatore, $data);
        $result = $client->run($succ_query);
        $record = $result->getRecord();
        if ($record != null)
        {
            $data_raggiunto_sup = new DateTime($record->get("dataSuperiore"));
            $valore_raggiunto_sup = $record->get("valoreRaggiuntoSuperiore");
        }

        if ($valore_raggiunto_inf == null && $valore_raggiunto_sup == null)
            return 0;
        else if ($valore_raggiunto_inf == null && $valore_raggiunto_sup != null && $valore_atteso_sup != $valore_atteso_iniziale)
        {
            // Trovo la data e il valore raggiunto precedente alla data di ricerca
            $prec_query = ('
                MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"R"})
                WHERE date(f.data) < date("%s") AND f.valoreAtteso = %d
                RETURN max(date(f.data)) as dataInferiore
            ');
            $prec_query = sprintf($prec_query, $indicatore, $data, $valore_atteso_iniziale);
            $result = $client->run($prec_query);
            $record = $result->getRecord();
            if ($record != null)
            {
                $data_raggiunto_inf = new DateTime($record->get("dataInferiore"));
                // $valore_atteso_inf = $record->get("valoreAttesoInferiore");
            }

            $data1 = new DateTime($data);
            $valore_raggiunto = ($data1->diff($data_raggiunto_inf)->days / ($data_raggiunto_sup->diff($data_raggiunto_inf)->days)) *
                                    ($valore_raggiunto_sup - $valore_ragg_iniziale) + $valore_ragg_iniziale;
        }
        else if ($valore_raggiunto_inf == null && $valore_raggiunto_sup != null && $valore_atteso_sup == $valore_atteso_iniziale)
        {
            $valore_raggiunto = $valore_atteso_inf;
        }
        else if ($valore_raggiunto_inf != null && $valore_raggiunto_sup == null)
        {
            $valore_raggiunto = $valore_raggiunto_inf;
        }
        else if ($valore_raggiunto_inf != null && $valore_raggiunto_sup != null)
        {
            $data1 = new DateTime($data);
            $valore_raggiunto = ($data1->diff($data_raggiunto_inf)->days / ($data_raggiunto_sup->diff($data_raggiunto_inf)->days)) *
                                    ($valore_raggiunto_sup - $valore_raggiunto_inf) + $valore_raggiunto_inf;
        }               
    }
    // Casi anomali
    if ($valore_raggiunto == $valore_atteso && $valore_atteso == $valore_atteso_iniziale)
        return 0;
    
    if ($valore_raggiunto != $valore_atteso && $valore_atteso == $valore_atteso_iniziale)
        return 1;
    
    $perc_compl = 0;
    if ($valore_atteso != $valore_atteso_iniziale)
    {
        switch($final)
        {
            case 0: // Mi riferisco al valore atteso in data attuale
                $perc_compl = ($valore_raggiunto - $valore_atteso_iniziale) / ($valore_atteso - $valore_atteso_iniziale);
                break;
            case 1: // Mi riferisco al valore atteso alla data finale
                $perc_compl = ($valore_raggiunto - $valore_atteso_iniziale) / ($valore_atteso_finale - $valore_atteso_iniziale);
        }
    }

    $perc_compl = $perc_compl > 1 ? 1: $perc_compl;
    
    $insert = '
        MATCH (a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-(b:ps_voci {tipo:"MO"})<-[:PS_VOCI]-(c:ps_voci {tipo:"AZ"})<-[:PS_VOCI]-(d:ps_voci {tipo:"TA"})<-[:PS_VOCI]-(f {cod: %d})
        SET %s = %.2f
        RETURN %s
    ';
    $field = $final == 0 ? "f.percComplAtt" : "f.percComplFin";
    $insert = sprintf($insert, $indicatore, $field, $perc_compl, $field);
    $client->run($insert);

    return $perc_compl;
}