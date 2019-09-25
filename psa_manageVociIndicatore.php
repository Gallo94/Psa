<?php 
require_once __DIR__ . '/vendor/autoload.php';
require_once 'psa_constant.inc.php';
require_once 'query.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$client = ClientBuilder::create()
    ->addConnection('default', 'http://neo4j:123@localhost:7474')
    ->build();

	$codindicatore = $_GET["cod"];

	$query = $vi_query;

    // /*
    //     $table['cols'] = header table
    //     $table['rows'] = data
    // */
	// $table = array();
	// $table['cols'] = array(
	// 	  // label individua le colonne della tabella la prima è l'etichetta e la seconda il valore (type:xxxx) 
	// 	  array('label' => 'Data',          'type' => 'date'  ),			// Data
	// 	  array('label' => 'Atteso',        'type' => 'number'),			// Valore Atteso 
	// 	  array('label' => 'TitoloAtteso',  'type' => 'string'),			// Titolo Valore  Atteso
	// 	  array('label' => 'TestoAtteso',   'type' => 'string'),				// Testo Nota Valore Atteso
	// 	  array('label' => 'Raggiunto',     'type' => 'number'),				// Valore Raggiunto
	// 	  array('label' => 'TitoloRaggiunto','type' =>'string'),			// Titolo Nota Valore Raggiunto
	// 	  array('label' => 'TestoRaggiiunto','type' =>'string'),			// Nota Valore Raggiunto 
	// );

	// $rows = array();

	// while($r = $q->fetch()) {
	// 		// crea una riga mettendola nel vettore $temp
	// 	preg_match('/(\d{4})-(\d{2})-(\d{2})\s(\d{2}):(\d{2}):(\d{2})/', $r['Data'], $match);
	//     $year = (int) $match[1];
	//     $month = (int) $match[2]; // convert to zero-index to match javascript's dates
	//     $day = (int) $match[3];
	//     $hours = (int) $match[4];
	//     $minutes = (int) $match[5];
	//     $seconds = (int) $match[6];
	// 	$temp = array();
	// 	$temp[] = array('v' => 'Date(' . date('Y,n,d,H,i,s', strtotime('-1 month',strtotime($r['Data']))).')'); 
	// 	$temp[] = array('v' => (float) $r['ValoreAtteso']); 
	// 	if ($r['NotaAtteso'] == null) {
	// 		$temp[] = null;
	// 		$temp[] = null;
	// 	} else {
	// 		$temp[] = array('v' => (string) 'A'); 
	// 		$temp[] = array('v' => (string) utf8_encode($r['NotaAtteso'])); 
	// 	}
	// 	$temp[] = array('v' => (float) $r['ValoreRaggiunto']); 
	// 	if ($r['NotaRaggiunto'] == null) {
	// 		$temp[] = null;
	// 		$temp[] = null;
	// 	} else {
	// 		$temp[] = array('v' => (string) 'R'); 
	// 		$temp[] = array('v' => (string) $r['NotaRaggiunto']); 
	// 	}
	// 	// aggiunge la riga creata al vettore delle righe 
	// 	$rows[] = array('c' => $temp);
	// }

	// $table['rows'] = $rows;
	// $jsonTableTrend = json_encode($table);

	// include(PATH_INCLUDE.'psa_open_connection.php');

	// try {
    //     $sql = "call ps_sl_voci_indicatore(".$_SESSION['userid'].",".$codindicatore.");";
    //     $q = $pdo->query($sql);
    //     $q->setFetchMode(PDO::FETCH_ASSOC);
    // } catch (PDOException $e) {
    //     die("Error occurred:" . $e->getMessage());
    // };

?>