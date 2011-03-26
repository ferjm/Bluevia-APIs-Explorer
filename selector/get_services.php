<?php
include_once('services.php');
$services=Services::getServices();
if(Constants::$debug) {
    while($service=@mysql_fetch_object($services)) 
        echo $service->service_name. '<br>';
}        
mysql_free_result($services);
?>
