<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'psa_constant.inc.php';
require_once 'query.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$client = ClientBuilder::create()
    ->addConnection('default', 'http://neo4j:123@localhost:7474')
    ->build();

$dlevel = isset($_SESSION['dashboard_starting_level']) ? $_SESSION['dashboard_starting_level'] : 'AS';

switch ($dlevel) {
    // Livello INDICATORE
    case 'IN':
        $query = $in_query;
        $cols = array(0,1,2,3,4,5,6);

        $result = $client->run($query);
        $rows = array();
        foreach ($result->records() as $record)
        {
            $node = array();
            $node[] = array('v' => $record->get('a.codAlf').' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get('a.nome')),
                            'f' => $record->get('a.codAlf').' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get('a.nome')));
            $node[] = array('v' => $record->get('b.codAlf').' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get('b.nome')),
                            'f' => $record->get('b.codAlf').' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get('b.nome')));
            $node[] = array('v' => $record->get('c.codAlf').' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get('c.nome')),
                            'f' => $record->get('c.codAlf').' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get('c.nome')));
            $node[] = array('v' => $record->get('d.codAlf').' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get('d.nome')),
                            'f' => $record->get('d.codAlf').' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get('d.nome')));
            $node[] = array('v' => $record->get('e.cod')   .' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get('e.nome')),
                            'f' => $record->get('e.codAlf')   .' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get('e.nome'))
                            //     '<a 
                            //         id="XX' . $record->get('e.cod') . 'XX" 
                            //             href="psa_manageVociIndicatore.php?cod=' . 
                            //                     $record->get('e.cod') . '">
                            //         dett.
                            //     </a>'
                            // );
                            
                        //     <a 
                        //     id="XX' . $record->get('e.cod') . 'XX" 
                        //         href="psa_manageVociIndicatore.php?cod=' . 
                        //                 $record->get('e.cod') . '">
                        //     dett.
                        // </a>
                    );


            $rows[] = array('c' => $node);
        }

        break;
    // Livello TARGET
    case 'TA':
        $query = $ta_query;
        $cols = array(0,1,2,3,5,6);

        $result = $client->run($query);
        $rows = array();
        foreach ($result->records() as $record)
        {
            $node = array();
            $node[] = array('v' => $record->get("a.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("a.nome")),
                            'f' => $record->get("a.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("a.nome")));
            $node[] = array('v' => $record->get("b.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("b.nome")),
                            'f' => $record->get("b.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("b.nome")));
            $node[] = array('v' => $record->get("c.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("c.nome")),
                            'f' => $record->get("c.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("c.nome")));
            $node[] = array('v' => $record->get("d.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("d.nome")),
                            'f' => $record->get("d.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("d.nome")));
        $rows[] = array('c' => $node);
        }        
        break;
    // Livello AZIONE
    case 'AZ':
        $query = $az_query;
        $cols = array(0,1,2,5,6);

        $result = $client->run($query);
        $rows = array();
        foreach ($result->records() as $record)
        {
            $node = array();
            $node[] = array('v' => $record->get("a.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("a.nome")),
                            'f' => $record->get("a.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("a.nome")));
            $node[] = array('v' => $record->get("b.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("b.nome")),
                            'f' => $record->get("b.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("b.nome")));
            $node[] = array('v' => $record->get("c.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("c.nome")),
                            'f' => $record->get("c.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("c.nome")));

            $rows[] = array('c' => $node);
        }        
        break;
    // Livello MACRO OBIETTIVO
    case 'MO':
        $query = $mo_query;
        $cols = array(0,1,5,6);

        $result = $client->run($query);
        $rows = array();
        foreach ($result->records() as $record)
        {
            $node = array();
            $node[] = array('v' => $record->get("a.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("a.nome")),
                            'f' => $record->get("a.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("a.nome")));
            $node[] = array('v' => $record->get("b.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("b.nome")),
                            'f' => $record->get("b.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("b.nome")));

            $rows[] = array('c' => $node);
        }        
        break;
    // Livello AREA STRATEGICA
    case 'AS':
        $query = $as_query;
        $cols = array(0,5,6);

        $result = $client->run($query);
        $rows = array();
        foreach ($result->records() as $record)
        {
            $node = array();
            $node[] = array('v' => $record->get("a.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("a.nome")),
                            'f' => $record->get("a.codAlf").' - '.preg_replace('@\x{FFFD}@u', 'à', $record->get("a.nome")));

            $rows[] = array('c' => $node);
        }
        break;
};

$table = array();
$table['cols'] = array(
    // label individua le colonne della tabella
    // la prima label è l'etichetta, la seconda il valore (type:number) 
    array('label' => 'Area Strategica', 'type' => 'string'),	// etichetta
    array('label' => 'Macro Obiettivo', 'type' => 'string'),	// etichetta
    array('label' => 'Azione', 'type' => 'string'),				// etichetta
    array('label' => 'Target', 'type' => 'string'),				// etichetta
    array('label' => 'Indicatore', 'type' => 'string'),			// etichetta
    array('label' => '% Attuale', 'type' => 'number'),		    // punteggio 
    array('label' => '% Finale', 'type' => 'number'),		    // punteggio 
);
$table['rows'] = $rows;
$json_table = json_encode($table);

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>PIANO STRATEGICO UNICAM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/logo.png">
        <link rel="stylesheet" href="css/color_type.css"/>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"/>
        <!-- CDN Bootstrap 4.0 css -->
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
        <!-- CDN Bootstrap core css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
        <!-- CDN MDB css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.10/css/mdb.min.css">
        
        <!-- CDN JQuery -->
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <!-- CDN Bootstrap core Js -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <!-- MDB core Js -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.10/js/mdb.min.js"></script>
        <!-- CDN Google Chart Js -->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {
                packages: ['table', 'annotationchart']
            });
            google.charts.setOnLoadCallback(drawThings);

            function drawThings(result) {
                var data = new google.visualization.DataTable(<?php echo $json_table ?>);

                var view = new google.visualization.DataView(data);
                view.setColumns(<?php echo json_encode($cols) ?>);

                // Data Table
                var table = new google.visualization.Table(document.getElementById('table_div'));
                table.draw(view, {
                    width: '100%',
                    height: 'auto',
                    showRowNumber: false,
                    alternatingRowStyle: true
                });

                // Expand/Collapse level in datatable
                $('#select-expand-all-levels').change(function(e) {
                    var levels = $('#select-expand-all-levels').val();
                    $.ajax({
                        url: "set_session.php",
                        type: "post",
                        data: { role: levels },
                        success: function(php_script_response) {
                            $(document).ajaxStop(function() { location.reload(true); });
                        }
                    });
                });

                // Button to export datatable
                $('#export').click(function () {
                    console.log("clicked");
                    var csvFormattedDataTable = google.visualization.dataTableToCsv(data);
                    var encodedUri = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csvFormattedDataTable);

                    var now = new Date();
                    var date = now.getFullYear() + "" + now.getMonth() + "" + now.getDate();

                    this.href = encodedUri;
                    this.download = 'piano-strategico_' + date + '.csv';
                    this.target = '_blank';
                });
            }
        </script>
    </head>
<body>
    <!--Navbar-->
    <nav id="navbar" class="navbar navbar-dark primary-color">
        <a class="navbar-brand" href="https://www.unicam.it/">
            <img src="/LogoUnicam.png" height="30" class="d-inline-block align-top"> Piano Strategico di Ateneo
        </a>
    </nav>
    <!-- Menù -->
    <div class="flex-container">
        <div>
        <?php if (($dlevel == 'IN') && isset($_GET['e.cod'])) echo '<a href="#'. $_GET['e.cod'].'" >Vai all\'indicatore sezionato </a><br>';?>
            <b>Livello di visualizzazione</b>
            <select class="form-control input-sm" id="select-expand-all-levels" style="width:180px">
                <option value="AS" <?php if ($dlevel == 'AS') echo 'selected'; ?>>1 - Area Strategica</option>
                <option value="MO" <?php if ($dlevel == 'MO') echo 'selected'; ?>>2 - Macro Obiettivo</option>
                <option value="AZ" <?php if ($dlevel == 'AZ') echo 'selected'; ?>>3 - Azione</option>
                <option value="TA" <?php if ($dlevel == 'TA') echo 'selected'; ?>>4 - Target</option>
                <option value="IN" <?php if ($dlevel == 'IN') echo 'selected'; ?>>5 - Indicatore</option>
            </select>
        </div>
        <button type="button" class="btn btn-outline-danger waves-effect">
            <i class="fas fa-download"></i>
            <a id="export" href="#">Esporta in .csv</a>
        </button>
    </div>
    <!-- Table -->
    <div class="control" id="table_div"></div>
</body>
</html>

    