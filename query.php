<?php
require 'db_connection.php';

// Return all child nodes of parent node
$query = ('
    MATCH (n:Voci)
    OPTIONAL MATCH (n)<-[:`voci - voci`]-(c)
    RETURN n.cod AS cod, n.codAlf AS codAlf, n.descrizione AS descr, n.tipo as type, COLLECT(c.cod) AS children
    ORDER BY cod
')
?>