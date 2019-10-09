<?php

// Area Strategica
$as_query = ('
MATCH (a:ps_voci {tipo:"AS"})
RETURN
a.nome, a.codAlf
');
	
// Macro Obiettivo
$mo_query = ('
MATCH p=(a:ps_voci {tipo: "AS"})<-[:PS_VOCI]-(b)
RETURN
a.nome, a.codAlf,
b.nome, b.codAlf
ORDER BY b.cod
    ');

// Azione
$az_query = ('
MATCH p=(a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
        (b:ps_voci {tipo:"MO"})<-[:PS_VOCI]-(c)
RETURN
a.nome, a.codAlf,
b.nome, b.codAlf,
c.nome, c.codAlf
ORDER BY c.cod
');

// Target
$ta_query = ('
MATCH p=(a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
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
MATCH p=(a:ps_voci {tipo:"AS"})<-[:PS_VOCI]-
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
MATCH p=(e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f)
RETURN  
f.data as Data,
f.valoreAtteso as ValoreAtteso,
f.valoreRaggiunto as ValoreRaggiunto,
f.natura as Natura,
f.nota as Nota
ORDER BY Data
');

// Indicatore Valore Raggiunto
$valore_rag_query =('
MATCH (e:ps_voci {cod: 101010101})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura: "A"})
WITH date(f.data) as dataa, f.data as Data, f.nota as Nota, f.id as ID
MATCH (e:ps_voci {cod: 101010101})<-[:PS_STORICO_VOCI]-(f)
RETURN
ID,
Data,
(duration.inDays(dataa,date(max(f.data))).days) * 1.0/
(duration.inDays(date(min(f.data)), date(max(f.data))).days) * 
(min(f.valoreRaggiunto) - max(f.valoreRaggiunto)) +
max(f.valoreRaggiunto)
as ValoreRaggiunto,
Nota
');
// Indicatore Valore Atteso
$valore_att_query =('
MATCH (e:ps_voci {cod: 101010101})<-[:PS_STORICO_VOCI]-(f:ps_storico {natura: "R"})
WITH date(f.data) as dataa

MATCH (e:ps_voci {cod: 101010101})<-[:PS_STORICO_VOCI]-(f)
RETURN
(duration.inDays(dataa,date(max(f.data))).days) * 1.0/
(duration.inDays(date(min(f.data)), date(max(f.data))).days) * 
(min(f.valoreAtteso) - max(f.valoreAtteso)) +
max(f.valoreAtteso)
as va
');
?>