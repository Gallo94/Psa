<?php

// Area Strategica
$as_query = ('
    MATCH (a:ps_voci)
    WHERE a.tipo="AS"
    RETURN a.nome, a.codAlf
    ');
	
// Macro Obiettivo
$mo_query = ('
    MATCH p=(a:ps_voci {tipo: "AS"})<-[:PS_VOCI]-(b)
    RETURN a.nome, a.codAlf,
           b.nome, b.codAlf
    ORDER BY b.cod
    ');

// Azione
$az_query = ('
    MATCH p=(a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
            (b:ps_voci {tipo:"MO"})<-[:PS_VOCI]-(c)
    RETURN a.nome, a.codAlf,
           b.nome, b.codAlf,
           c.nome, c.codAlf
    ORDER BY c.cod
    ');

// Target
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


// Indicatore
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
// Voci Indicatore
$vi_query =('
MATCH p=(a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
        (b:ps_voci {tipo:"MO"})<-[:PS_VOCI]-
        (c:ps_voci {tipo:"AZ"})<-[:PS_VOCI]-
        (d:ps_voci {tipo:"TA"})<-[:PS_VOCI]-
        (e {cod: 101040101})<-[:PS_STORICO_VOCI]-(f)
RETURN  
e.nome as Indicatore,
e.codAlf as Cod,

f.data as Data,
f.valoreAtteso as ValoreAtteso,
f.valoreRaggiunto as ValoreRaggiunto,
f.natura as Natura,
f.nota as Nota
ORDER BY Data
')

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