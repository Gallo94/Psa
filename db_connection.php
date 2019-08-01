<?php
require_once (__DIR__.'/vendor/autoload.php');

use GraphAware\Neo4j\Client\ClientBuilder;

$client = ClientBuilder::create()
    ->addConnection('default', 'http://neo4j:psa@localhost:7474')
    ->build();
?>