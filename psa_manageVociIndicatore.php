<?php 
require_once 'db_connection.php';

$codindicatore = $_GET["cod"];

$query = $vi_query;

$query = sprintf($query, $codindicatore);

$result = $client->run($query);
$rows = array();
foreach ($result->records() as $r)
{
	// crea una riga mettendola nel vettore $temp
	preg_match('/(\d{4})-(\d{2})-(\d{2})/', $r->get('Data'), $match);
	$year = (int) $match[1];
	$month = (int) $match[2]; // convert to zero-index to match javascript's dates
	$day = (int) $match[3];
	$temp = array();

	$temp[] = array('v' => 'Date(' . date('Y,n,d', strtotime('-1 month', strtotime($r->get('Data')))).')'); 
	$temp[] = array('v' => (float) $r->get('ValoreAtteso')); 
	if ($r->get('Nota') == null) {
		$temp[] = null;
		$temp[] = null;
	} else {}
	$temp[] = array('v' => (float) $r->get('ValoreRaggiunto')); 
	if ($r->get('Nota') == null) {
		$temp[] = null;
		$temp[] = null;
	} else {}
	
	// aggiunge la riga creata al vettore delle righe 
	$rows[] = array('c' => $temp);
}

$table = array();	// ho due elementi : $table['cols'] per l'intestazione della tabella e $table['rows'] per i dati
$table['cols'] = array(
		// label individua le colonne della tabella la prima è l'etichetta e la seconda il valore (type:xxxx) 
		array('label' => 'Data', 'type' => 'date'),						// Data
		array('label' => 'Atteso', 'type' => 'number'),					// Valore Atteso 
		array('label' => 'Raggiunto', 'type' => 'number'),				// Valore Aggiunto
);

$table['rows'] = $rows;
$jsonTableTrend = json_encode($table);
?>

<html>
	<head>
	    <title>UNICAM GESTIONE INDICATORI</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	    <meta charset="utf-8" />
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Logo Unicam -->
		<link rel="icon" href="/logo.png">
		<!-- CSS -->
        <link rel="stylesheet" href="css/color_type.css"/>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"/>
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
		<script type='text/javascript'>
			google.charts.load('current', {'packages':['annotationchart']});
			google.charts.setOnLoadCallback(drawChart);

			function drawChart() {
				var data = new google.visualization.DataTable(<?php echo $jsonTableTrend; ?>);
				var chart = new google.visualization.AnnotationChart(document.getElementById('chart_div'));

				var options = {
				  displayAnnotations: true
				};

				chart.draw(data, options);
			}
		</script>
	</head>
	<body>
		<!--Navbar-->
		<nav id="navbar" class="navbar navbar-dark primary-color">
			<a class="navbar-brand" href="index.php">
				<img src="/LogoUnicam.png" height="30" class="d-inline-block align-top" style="margin-right: 20px"> Gestione Voci Indicatore
			</a>
		</nav>
		<div class="container-fluid">
			<!-- Chart  -->
			<div id='chart_div' style='width: 800px; height: 400px; margin-top: 10px'/>
		</div>
		<!-- Card  -->
		<div class="card" style="margin-top: 10px;">
			<h3 class="card-header font-weight-bold text-uppercase py-3">Indicatore n° <?php echo $r->get("Cod");?></h3>
				<div id="table">
					<!-- Table -->
				<div class="table-responsive text-nowrap">
					<table class="table table-responsive-md text-center" id="listaVociIdentificatore">
						<!-- Header table  -->
						<thead>
							<tr>
								<th scope="col">Data</th>
								<th scope="col">Valore Atteso</th>
								<th scope="col">Valore Raggiunto</th>
								<th scope="col">Natura</th>
								<th scope="col">Nota</th>
								<th scope="col">Azioni</th>
							</tr>
						</thead>
						<?php foreach($result->records() as $r) { ?>
							<tr class="active" id="idindicatore">
								<td scope="row">
									<input type="date" class="form-control datepicker" id="dataVoce" data-date-format="yyyy/mm/dd"
									value="<?php echo $r->get('Data') ?>" min="2018-01-01" max="2023-12-31">
								</td>
								<td scope="row" width="10%">
									<input type="number" class="form-control datepicker" id="valoreAtt"
									value=<?php echo $r->get("ValoreAtteso"); ?>>
								</td>
								<td scope="row" width="10%">
									<input type="number" class="form-control datepicker" id="valoreRag"
									value=<?php echo $r->get("ValoreRaggiunto"); ?>>
								</td>
								<td scope="row" width="10%">
									<select class="form-control" value="<?php echo $r->get("Natura"); ?>" id="natura">
										<option value = "A"> Atteso</option>
										<option value = "R"> Raggiunto</option>
									</select>
								</td>
								<td scope="row" width="35%">
									<input type="input" class="form-control" id="notaVoce"
									value="<?php echo $r->get('Nota')?>">
								</td>
								<td scope="row">
									<button type="button" class="btn btn-primary" id="buttonUpdate"><i class="fas fa-check"></i></button>
									<button type="button" class="btn btn-danger" id="buttonDelete"><i class="fas fa-trash"></i></button>
								</td>
							</tr>
						<?php } ?>
						<tr id="nuovaVoce">
							<td scope="row">
									<input type="date" class="form-control datepicker" id="dataVoceInsert" data-date-format="yyyy/mm/dd" min="2018-01-01" max="2023-12-31">
								</td>
								<td scope="row" width="10%">
									<input type="number" class="form-control datepicker" id="valoreAttInsert">
								</td>
								<td scope="row" width="10%">
									<input type="number" class="form-control datepicker" id="valoreRagInsert">
								</td>
								<td scope="row" width="10%">
									<select class="form-control" id="naturaInsert">
										<option value = "A"> Atteso</option>
										<option value = "R"> Raggiunto</option>
									</select>
								</td>
								<td scope="row" width="35%">
									<input type="input" class="form-control" placeholder="Inserisci una nota" id="notaVoceInsert">
								</td>
								<td scope="row">
									<button type="button" class="btn btn-success float-right" id="buttonInsert" style="right: 60px;"><i class="fas fa-plus"></i></button>
								</td>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function()
			{
				var wrapper = $("#listaVociIdentificatore");
				// update voce indicatore
				$(wrapper).on("click","#buttonUpdate", function(e)
				{
					e.preventDefault();
					var id = $(this).attr('accesskey');
					var cod = <?php echo $codindicatore?>;
					var dataVoce = $('#dataVoce'+id).val();
					var valoreAtt = $('#valoreAtt'+id).val();
					var valoreRag = $('#valoreRag'+id).val();
					var naturaVoce = $('#select'+id).val();
					var notaVoce = $('#notaVoce'+id).val();
					var operazioneVoce = 'Update';

					$.ajax(
					{
						url: "psa_crudVoceIndicatore.php",
						type: "post",
						data: {ID: id, Cod: cod, Data: dataVoce, ValoreAtteso: valoreAtt, ValoreRaggiunto: valoreRag, Natura: naturaVoce,Nota: notaVoce,Operazione: operazioneVoce},
						success:function (php_script_response) {
							$(document).ajaxStop(function() { location.reload(true); });
						}
					});
				});
			});
		</script>
	</body>
</html>