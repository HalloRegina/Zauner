<?php

include 'config.php';

try
{
    include 'functions.php';


    $query = 'select d.dra_id as "Nr", 
d.dra_name as "Name des Stücks", 
concat_ws(\' \', p.per_vname, p.per_nname) as "Autor", 
(select min(e.eve_termin) from event e where e.dra_id = d.dra_id) as "Erstaufführung"
from person p, rolle r, drama d
where p.rol_id = r.rol_id
  and r.rol_id = 4
  and d.autor_id = p.per_id
order by d.dra_id asc;';

    $stmt = $con->prepare($query);
    $stmt->execute();


    showTableFromQuery($stmt);
}
catch (Exception $e)
{
    echo $e->getMessage();
}
