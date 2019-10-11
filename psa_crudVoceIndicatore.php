<?php
require_once 'db_connection.php';
require_once 'ev_values.php';
require_once 'evaluate_perc.php';

if(isset($_POST['Operazione']) && ($_POST['Operazione'] == 'Update'))
{
    $id = $_POST["Id"];
    $cod = $_POST["Cod"];
    $data = $_POST["Data"];
    $valore_att = $_POST["ValoreAtteso"];
    $valore_rag = $_POST["ValoreRaggiunto"];
    $natura = $_POST["Natura"];
    $nota = $_POST["Nota"];

    $query = $update_query;
    $query = sprintf($query, $cod, $id, $data, $valore_att, $valore_rag, $natura, $nota);
    $result = $client->run($query);
}
else if (isset($_POST['Operazione']) && ($_POST['Operazione'] == 'Insert'))
{
    $last_id_query = 'MATCH (e:ps_voci)<-[:PS_STORICO_VOCI]-(f:ps_storico) RETURN max(f.id) as Id';
    $result = $client->run($last_id_query);
    $max_id = $result->getRecord()->get("Id");

    $id = $max_id + 1;
    $cod = $_POST["Cod"];
    $data = $_POST["Data"];
    $valoreAtt = $_POST["ValoreAtteso"];
    $valoreRag = $_POST["ValoreRaggiunto"];
    $natura = $_POST["Natura"];
    $nota = $_POST["Nota"];

    $query = $insert_query;
    $query = sprintf($query, $id, $data, $natura, $nota, $valoreAtt, $valoreRag, $cod);
    $result = $client->run($query);
}
else if (isset($_POST['Operazione']) && ($_POST['Operazione'] == 'Delete'))
{
    echo "Delete"; 

    $id = $_POST["Id"];
    // $cod = $_POST["Cod"];
    // $data = $_POST["Data"];
    // $final = $data == "2023-12-31" ? 1 : 0;

    // Delete row
    $query = $delete_query;
    $query = sprintf($query, $id);
    $result = $client->run($query);

    // // Update db
    // aggiorna_indicatore($client, $cod);
    // evaluate_perc_in($client, $cod, $data, $final);
}

?>