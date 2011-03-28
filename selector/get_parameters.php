<?php
include_once('services.php');
$services = new Services();
$method_id = 1; // just for testing 
if($services)
    $params = $services->getParameters($method_id);
if(Constants::$debug) {
    while($param = @mysql_fetch_object($params)) 
        echo $param->param_name. '<br>';
}        
if($params) mysql_free_result($params);
unset($services);
?>