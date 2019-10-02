// Voci Indicatore
$vi_query =('
MATCH p=(e:ps_voci {cod: %d})<-[:PS_STORICO_VOCI]-(f)
RETURN  
f.data as Data,
case f.natura when "A" then f.valoreAtteso else 
('
MATCH p=(e:ps_voci {cod: 101010101})<-[:PS_STORICO_VOCI]-(f)
RETURN
(duration.inDays(date(f.data),date(max(f.data))).days) * 1.0/
(duration.inDays(date(min(f.data)), date(max(f.data))).days) * 
(min(f.valoreAtteso) - max(f.valoreAtteso)) +
max(f.valoreAtteso)
as result
');

end as ValoreAtteso,
case f.natura when "R"  f.valoreRaggiunto else 0 end as ValoreRaggiunto,
f.natura as Natura,
f.nota as Nota
ORDER BY Data
');
// Grafico Indicatore
$piano_query =('
MATCH p=(e:ps_voci {cod: 101010101})<-[:PS_STORICO_VOCI]-(f)
RETURN
(duration.inDays(date(%s),date(max(f.data))).days) * 1.0/
(duration.inDays(date(min(f.data)), date(max(f.data))).days) * 
(min(f.valoreAtteso) - max(f.valoreAtteso)) +
max(f.valoreAtteso)
as result
')