<?php
include_once('services.php');
$services = new Services();
$service_id = 1; // just for testing 
if($services)
    $methods = $services->getMethods($service_id);
if(Constants::$debug) {
    while($method = @mysql_fetch_object($methods)) 
        echo $method->method_name. '<br>';
}        
if($methods) mysql_free_result($methods);
unset($services);
?>
