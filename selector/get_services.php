<?php
include_once('services.php');
$services_o = new Services();
if($services_o)
    $services = $services_o->getServices();
while($service=@mysql_fetch_object($services)) 
    print "<div id=\"selector_row\" onclick=\"loadApiFunctions(".$service->service_id.")\"><p id=\"selector_value\">".$service->service_name."</p></div>";
if($services) mysql_free_result($services);
unset($services_o);
?>
