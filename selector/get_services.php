<?php
include_once('services.php');
$services_o = new Services();
if($services_o)
    $services = $services_o->getServices();
if(Constants::$debug) {
    while($service=@mysql_fetch_object($services)) 
        echo $service->service_name. '<br>';
}        
if($services) mysql_free_result($services);
unset($services_o);
?>
