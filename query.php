<?php

// Area Strategica
$as_query = ('
MATCH (a:ps_voci {tipo:"AS"})
RETURN
a.nome, a.codAlf
');
	
// Macro Obiettivo
$mo_query = ('
MATCH (a:ps_voci {tipo: "AS"})<-[:PS_VOCI]-(b)
RETURN
a.nome, a.codAlf,
b.nome, b.codAlf
ORDER BY b.cod
    ');

// Azione
$az_query = ('
MATCH (a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
        (b:ps_voci {tipo:"MO"})<-[:PS_VOCI]-(c)
RETURN
a.nome, a.codAlf,
b.nome, b.codAlf,
c.nome, c.codAlf
ORDER BY c.cod
');

// Target
$ta_query = ('
MATCH (a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
        (b:ps_voci {tipo:"MO"})<-[:PS_VOCI]-
        (c:ps_voci {tipo:"AZ"})<-[:PS_VOCI]-(d)
RETURN
a.nome, a.codAlf,
b.nome, b.codAlf,
c.nome, c.codAlf,
d.nome, d.codAlf
ORDER BY d.cod
');

// Indicatore
$in_query = ('
MATCH (a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
        (b:ps_voci {tipo:"MO"})<-[:PS_VOCI]-
        (c:ps_voci {tipo:"AZ"})<-[:PS_VOCI]-
        (d:ps_voci {tipo:"TA"})<-[:PS_VOCI]-(e)
RETURN
a.nome, a.codAlf,
b.nome, b.codAlf,
c.nome, c.codAlf,
d.nome, d.codAlf,
e.nome, e.codAlf, e.cod, e.percComplAtt, e.percComplFin
ORDER BY e.cod
');

// Voci Indicatore
$vi_query =('
MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f)
RETURN
e.cod as Cod,
f.id as Id,
f.data as Data,
f.valoreAtteso as ValoreAtteso,
f.valoreRaggiunto as ValoreRaggiunto,
f.natura as Natura,
f.nota as Nota
ORDER BY Data
');

// Rimuovo un nodo dello storico dell' indicatore
$delete_query =('
MATCH (e:ps_voci)<-[:PS_STORICO_VOCI]-(f:ps_storico {id: %d})
DETACH DELETE f
');
// Aggiunta record allo storico dell' indicatore
$insert_query = ('
CREATE (n:ps_storico { id: %d, data: date("%s"), natura: "%s", nota: "%s", valoreAtteso: %.2f, valoreRaggiunto: %.2f })-
[:PS_STORICO_VOCI]->(m:ps_voci {cod: %d})
RETURN n;
');
// Aggiorno lo storico dell' indicatore
$update_query = ('
MATCH (e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f)
WHERE f.id = %d
SET f.data="%s", f.valoreAtteso=%.2f, f.valoreRaggiunto=%.2f, f.natura="%s", f.nota="%s" 
RETURN
f.data as Data,
f.valoreAtteso as ValoreAtteso,
f.valoreRaggiunto as ValoreRaggiunto,
f.natura as Natura,
f.nota as Nota
ORDER BY Data
')
?>