<?php
// require 'db_connection.php';

// AS
$as_query = ('
    MATCH (a:ps_voci)
    WHERE a.tipo="AS"
    RETURN a.nome, a.codAlf
    ');
	
// MO
$mo_query = ('
    MATCH p=(a:ps_voci {tipo: "AS"})<-[:PS_VOCI]-(b)
    RETURN a.nome, a.codAlf,
           b.nome, b.codAlf
    ORDER BY b.cod
    ');

// AZ
$az_query = ('
    MATCH p=(a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
            (b:ps_voci {tipo:"MO"})<-[:PS_VOCI]-(c)
    RETURN a.nome, a.codAlf,
           b.nome, b.codAlf,
           c.nome, c.codAlf
    ORDER BY c.cod
    ');

// TA
$ta_query = ('
    MATCH p=(a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
            (b:ps_voci {tipo:"MO"})<-[:PS_VOCI]-
            (c:ps_voci {tipo:"AZ"})<-[:PS_VOCI]-(d)
    RETURN a.nome, a.codAlf,
           b.nome, b.codAlf,
           c.nome, c.codAlf,
           d.nome, d.codAlf
    ORDER BY d.cod
    ');


// IN
$in_query = ('
MATCH p=(a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
    (b:ps_voci {tipo:"MO"})<-[:PS_VOCI]-
    (c:ps_voci {tipo:"AZ"})<-[:PS_VOCI]-
    (d:ps_voci {tipo:"TA"})<-[:PS_VOCI]-(e)
RETURN a.nome, a.codAlf,
       b.nome, b.codAlf,
       c.nome, c.codAlf,
       d.nome, d.codAlf,
       e.nome, e.codAlf, e.cod
ORDER BY e.cod
    ');

/*
MATCH p=(a)-[r:PS_STORICO_VOCI]->(b)
RETURN b.cod as Cod,
a.data as Data,
a.valoreRaggiunto as valoreRaggiunto,
a.valoreAtteso as valoreAtteso,
a.nota as Nota
ORDER BY Cod
*/

?>