<?php
require 'query.php';

$result = $client->run($query);
$nodes = array();
$dataatt;
$datafin;
foreach ($result->records() as $record) {
    $value = array();
    $value['cod'] = $record->get('n.cod');
    $value['codAlf'] = $record->get('n.codAlf');
    $value['dataAtt'] = date('d/m/Y', strtotime($record->get('n.dataAtt')));
    $value['dataFin'] = date('d/m/Y', strtotime($record->get('n.dataFin')));
    $value['descrizione'] = preg_replace('@\x{FFFD}@u', 'à', $record->get('n.descrizione'));
    $value['nome'] = $record->get('n.nome');
    $value['nvociSorelle'] = $record->get('n.nvociSorelle');
    $value['ordine'] = $record->get('n.ordine');
    $value['percComplAtt'] = $record->get('n.percComplAtt');
    $value['percComplFin'] = $record->get('n.percComplFin');
    $value['peso'] = $record->get('n.peso');
    $value['pesoPercAtt'] = $record->get('n.pesoPercAtt');
    $value['pesoPercFin'] = $record->get('n.pesoPercFin');
    $value['tipo'] = $record->get('n.tipo');

    if ($value['cod'] == null) continue;

    $nodes[] = $value;
}

$cols = "[{\"data\":\"cod\"},{\"data\":\"codAlf\"},{\"data\":\"descrizione\"},{\"data\":\"tipo\"},{\"data\":\"dataAtt\"},{\"data\":\"dataFin\"}]";
// $cols_as = "[{\"data\":\"tipo\"},{\"data\":\"percComplAtt\"},{\"data\":\"percComplFin\"}]";
// $cols_mo = "[{\"data\":\"descrizione\"},{\"data\":\"descrizione\"},{\"data\":\"percComplAtt\"},{\"data\":\"percComplFin\"}]";

// $nodes_as = array();
// foreach ($nodes as $n) {
//     if ($n['tipo'] == "AS") {
//         $nodes_as[] = $n;
//     }
// }

// $nodes_mo = array();
// foreach ($nodes as $n) {
//     if ($n['tipo'] == "MO") {
//         $nodes_mo[] = $n;
//         $parent = substr($n['cod'], 0, 1);
//         foreach ($nodes_as as $as) {
//             if ($as['cod'] == $parent) { }
//         }
//     }
// }

?>
<html>

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css" rel="stylesheet" />
    <link href="css/color_type.css" rel="stylesheet" />
</head>

<body>

    <!--Navbar-->
    <nav class="navbar navbar-dark primary-color">
        <a class="navbar-brand" href="https://www.unicam.it/">
            <img src="/LogoUnicam.png" height="30" class="d-inline-block align-top"> Piano Strategico
        </a>
    </nav>

    <div id="main_table_container">
        <table id="main_table" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Cod</th>
                    <th scope="col">CodAlf</th>
                    <th scope="col">Descrizione</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Data Attuale</th>
                    <th scope="col">Data Fine</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js"></script>
<script>
    var json = <?php echo json_encode($nodes) ?>;
    var cols = <?php echo $cols ?>;

    $(document).ready(function() {
        $('#main_table').DataTable({
            "data": json,
            "columns": cols
        });
    });
</script>