<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'psa_constant.inc.php';
require_once 'query.php';

use GraphAware\Neo4j\Client\ClientBuilder;

$client = ClientBuilder::create()
    ->addConnection('default', 'http://neo4j:123@172.27.12.44:7474')
    ->build();
?>