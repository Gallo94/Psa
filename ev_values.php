<?php

function ev_valori_attesi($client, $cod, $data)
{
    // Exec query
    $query = '
        MATCH (n:ps_datagen)
        MATCH (a:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(b:ps_storico {natura:"A"})
        RETURN date(n.psDatini) as data_iniziale_piano, date(n.psDatfin) as data_finale_piano,
        date(min(b.data)) as data_iniziale_indicatore, date(max(b.data)) as data_finale_indicatore,
        min(b.valoreAtteso) as valoreAttesoIniziale, max(b.valoreAtteso) as valoreAttesoFinale,
        count(b.valoreAtteso) as numeroValoriAttesi
    ';
    $query = sprintf($query, $cod);
    $result = $client->run($query);
    $record = $result->getRecord();

    // Store data
    $data_iniziale_piano = $record->get("data_iniziale_piano");
    $data_finale_piano = $record->get("data_finale_piano");
    $data_iniziale_indicatore = $record->get("data_iniziale_indicatore");
    $data_finale_indicatore = $record->get("data_finale_indicatore");
    $valoreAttesoIniziale = $record->get("valoreAttesoIniziale");
    $valoreAttesoFinale = $record->get("valoreAttesoFinale");
    $numero_valori_attesi = $record->get("numeroValoriAttesi");
    if ($numero_valori_attesi < 2)
        return -1;
    
    // Clamp data
    if ($data < $data_iniziale_piano)
        $data = $data_iniziale_piano;
    if ($data > $data_finale_piano)
        $data = $data_finale_piano;

    // Set valore atteso
    $valore_atteso = 0;
    if ($data < $data_iniziale_indicatore)
        $valore_atteso = $valoreAttesoIniziale;
    else if ($data > $data_finale_indicatore)
        $valore_atteso = $valoreAttesoFinale;
    else if ($data == $data_iniziale_indicatore || $data == $data_finale_indicatore)
    {
        $query ='
            MATCH p=(e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico) WHERE date(f.data) = date("%s") AND f.natura = "A"
            RETURN f.valoreAtteso as ValoreAtteso
        ';
        $query = sprintf($query, $cod, $data);
        $result = $client->run($query);
        $valore_atteso = $result->getRecord();
    }
    else if ($data > $data_iniziale_indicatore || $data < $data_finale_indicatore)
    {
        $valore_atteso = null;
        $query ='
            MATCH p=(e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico) WHERE date(f.data) = date("%s") AND f.natura = "A"
            RETURN f.valoreAtteso as ValoreAtteso
        ';
        $query = sprintf($query, $cod, $data);
        $result = $client->run($query);
        $valore_atteso = $result->getRecord();
        if ($valore_atteso == null)
        {
            $query = '
                MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"A"})
                WHERE date(f.data) < date("%s")
                RETURN max(date(f.data)) as dataInferiore, max(f.valoreAtteso) as valoreAttesoInferiore
            ';
            $query = sprintf($query, $cod, $data);
            $result = $client->run($query);
            $record = $result->getRecord();
            $data_inferiore = new DateTime($record->get("dataInferiore"));
            $valore_atteso_inferiore = $record->get("valoreAttesoInferiore");

            $query = '
                MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"A"})
                WHERE date(f.data) > date("%s")
                RETURN min(date(f.data)) as dataSuperiore, min(f.valoreAtteso) as valoreAttesoSuperiore
            ';
            $query = sprintf($query, $cod, $data);
            $result = $client->run($query);
            $record = $result->getRecord();
            $data_superiore = new DateTime($record->get("dataSuperiore"));
            $valore_atteso_superiore = $record->get("valoreAttesoSuperiore");

            $data = new DateTime($data);
            $valore_atteso =  ($data->diff($data_inferiore)->days / $data_superiore->diff($data_inferiore)->days) * ($valore_atteso_superiore - $valore_atteso_inferiore) + $valore_atteso_inferiore;
        }
    }

    return $valore_atteso;
}

function ev_valori_raggiunti($client, $cod, $data)
{
    // Exec query
    $query = '
        MATCH (n:ps_datagen)
        RETURN date(n.psDatini) as data_iniziale_piano, date(n.psDatfin) as data_finale_piano
    ';
    $query = sprintf($query, $cod);
    $result = $client->run($query);
    $record = $result->getRecord();

    // Store data
    $data_iniziale_piano = $record->get("data_iniziale_piano");
    $data_finale_piano = $record->get("data_finale_piano");

    if ($data == $data_iniziale_piano)
    {
        $query = '
            MATCH (a:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(b:ps_storico)
            WHERE date(b.data) = date("%s")
            RETURN b.valoreAtteso as valore_atteso
        ';
        $query = sprintf($query, $cod, $data);
        $result = $client->run($query);
        $record = $result->getRecord();
        return $record->get('valore_atteso');
    }

    $query = '
        MATCH (a:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(b:ps_storico {natura:"R"})
        RETURN date(min(b.data)) as data_iniziale_indicatore, date(max(b.data)) as data_finale_indicatore,
        max(b.valoreRaggiunto) as valoreRaggiuntoFinale
    ';
    $query = sprintf($query, $cod);
    $result = $client->run($query);
    $record = $result->getRecord();

    $data_iniziale_indicatore = $record->get("data_iniziale_indicatore");
    $data_finale_indicatore = $record->get("data_finale_indicatore");
    $valoreRaggiuntoFinale = $record->get("valoreRaggiuntoFinale");

    $valore_raggiunto = 0;
    if ($data < $data_iniziale_indicatore)
    {
        $query = '
            MATCH (a:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(b:ps_storico {natura:"A"})
            RETURN min(b.valoreAtteso) as valore_raggiunto_inferiore
        ';
        $query = sprintf($query, $cod);
        $result = $client->run($query);
        $record = $result->getRecord();       
        $valore_raggiunto_inferiore = $record->get("valore_raggiunto_inferiore"); // <====
        
        $query = '
            MATCH (a:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(b:ps_storico {natura:"A"})
            WHERE b.valoreAtteso = %d
            RETURN max(date(b.data)) as data_inferiore
        ';
        $query = sprintf($query, $cod, $valore_raggiunto_inferiore);
        $result = $client->run($query);
        $record = $result->getRecord();
        $data_inferiore = new DateTime($record->get("data_inferiore"));                          // <====

        $query = '
            MATCH (a:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(b:ps_storico {natura:"R"})
            WHERE date(b.data) > date("%s") 
            RETURN min(date(b.data)) as data_superiore, min(b.valoreRaggiunto) as valore_raggiunto_superiore
        ';
        $query = sprintf($query, $cod, $data);
        $result = $client->run($query);
        $record = $result->getRecord();

        $data_superiore = new DateTime($record->get("data_superiore"));
        $valore_raggiunto_superiore = $record->get("valore_raggiunto_superiore");

        $data1 = new DateTime($data);
        $valore_raggiunto =  ($data1->diff($data_inferiore)->days / $data_superiore->diff($data_inferiore)->days) * ($valore_raggiunto_superiore - $valore_raggiunto_inferiore) + $valore_raggiunto_inferiore;
    }

    if ($data > $data_finale_indicatore)
    {
        $valore_raggiunto = $valoreRaggiuntoFinale;
    }
    else if ($data >= $data_iniziale_indicatore && $data <= $data_finale_indicatore)
    {
        $valore_raggiunto = null;
        $query = '
            MATCH (a:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(b:ps_storico {natura:"R"})
            WHERE date(b.data) = date("%s") 
            RETURN b.valoreRaggiunto as valore_raggiunto
        ';
        $query = sprintf($query, $cod, $data);
        $result = $client->run($query);
        $record = $result->getRecord();

        $valore_raggiunto = $record->get("valore_raggiunto");
        if ($valore_raggiunto == null)
        {
            $query = '
                MATCH (a:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(b:ps_storico {natura:"R"})
                WHERE date(b.data) < date("%s") 
                RETURN max(b.valoreRaggiunto) as valore_raggiunto_inferiore, max(date(b.data)) as data_inferiore
            ';
            $query = sprintf($query, $cod, $data);
            $result = $client->run($query);
            $record = $result->getRecord();

            $valore_raggiunto_inferiore = $record->get("valore_raggiunto_inferiore");
            $data_inferiore = $record->get("data_inferiore");

            $query = '
                MATCH (a:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(b:ps_storico {natura:"R"})
                WHERE date(b.data) > date("%s") 
                RETURN min(b.valoreRaggiunto) as valore_raggiunto_superiore, min(date(b.data)) as data_superiore
            ';
            $query = sprintf($query, $cod, $data);
            $result = $client->run($query);
            $record = $result->getRecord();

            $valore_raggiunto_superiore = $record->get("valore_raggiunto_superiore");
            $data_superiore = $record->get("data_superiore");

            $data1 = new DateTime($data);
            $valore_raggiunto =  ($data1->diff($data_inferiore)->days / $data_superiore->diff($data_inferiore)->days) * ($valore_raggiunto_superiore - $valore_raggiunto_inferiore) + $valore_raggiunto_inferiore;    
        }
    }

    return $valore_raggiunto;
}

function ps_sl_voci_indicatore($client, $cod, $curdata)
{
    $query =('
        MATCH p=(e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f)
        RETURN
        f.valoreAtteso as ValoreAtteso,
        f.valoreRaggiunto as ValoreRaggiunto,
        f.natura as Natura,
        f.data as Data
        ORDER BY Data
        
    ');
    $query = sprintf($query, $cod);
    $result = $client->run($query);
    
    foreach ($result->getRecords() as $record)
    {
        $valore_atteso = $record->get("ValoreAtteso");
        $valore_raggiunto = $record->get("ValoreRaggiunto");
        $natura = $record->get("Natura");
        $data = $record->get("Data");

        $value = 0;
        if ($natura == 'A' && $valore_raggiunto == null)
        {
            $value = ev_valori_raggiunti($client, $cod, $curdata);
            $query = '
                MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"A"})
                WHERE date(f.data) = date("%s")
                SET f.valoreRaggiunto = %.2f
                RETURN f.valoreRaggiunto as valoreRaggiunto
            ';

            $query = sprintf($query, $cod, $data, $value);
            $result = $client->run($query);
            $record = $result->getRecord();
        }
        else if ($natura == 'R' && $valore_atteso == null)
        {
            $value = ev_valori_attesi($client, $cod, $curdata);
            $query = '
                MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura:"R"})
                WHERE date(f.data) = date("%s")
                SET f.valoreAtteso = %.2f
                RETURN f.valoreAtteso as valoreAtteso
            ';
            $query = sprintf($query, $cod, $data, (float)$value);
            $result = $client->run($query);
            $record = $result->getRecord();
        }
    }
}