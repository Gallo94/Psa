<?php
require 'db_connection.php';

$query = ('
    MATCH (n:Voci)
    RETURN n.cod, n.codAlf, n.dataAtt, n.dataFin, n.descrizione, n.nome,
    n.nvociSorelle, n.ordine, n.percComplAtt, n.percComplFin, n.peso,
    n.pesoPercAtt, n.pesoPercFin, n.tipo
');

// // Return all child nodes of parent node
// $query = ('
//     MATCH (n:Voci)
//     OPTIONAL MATCH (n)<-[:voci - voci]-(c)
//     RETURN n.cod AS cod, n.codAlf AS codAlf, n.descrizione AS descr, n.tipo as type, COLLECT(c.cod) AS children
//     ORDER BY cod
// ');
?>