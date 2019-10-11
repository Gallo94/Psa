<?php
require_once 'db_connection.php';
require_once 'ev_values.php';
require_once 'evaluate_perc.php';

if(isset($_POST['Operazione']) && ($_POST['Operazione'] == 'Update'))
{
    echo "Update";

    $id = $_POST["ID"];
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
    // echo "Insert";

    // $id = $_POST["ID"];
    // $cod = $_POST["Cod"];
    // $data = $_POST["Data"];
    // $valore = $_POST["Valore"];
    // $natura = $_POST["Natura"];
    // $nota = $_POST["Nota"];

    //     $query = $insert_query;
    // if ($natura == 'A')
    //     $query = sprintf($query, $cod, $data, $valore, null, $natura, $nota);
    // else
    //     $query = sprintf($query, $cod, $data, null, $valore, $natura, $nota);

    // $result = $client->run($query);
}
else if (isset($_POST['Operazione']) && ($_POST['Operazione'] == 'Delete'))
{
    // echo "Delete"; 

    // $id = $_POST["ID"];
    // $cod = $_POST["Cod"];
    // $data = $_POST["Data"];
    // $final = $data == "2023-12-31" ? 1 : 0;

    // // Delete row
    // $query = $delete_query;
    // $query = sprintf($query, $id);
    // $result = $client->run($query);

    // // Update db
    // aggiorna_indicatore($client, $cod);
    // evaluate_perc_in($client, $cod, $data, $final);
}

?>