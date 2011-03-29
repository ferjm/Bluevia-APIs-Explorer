<?php
include_once('services.php');
$services = new Services();
$service_id = $_GET['service_id']; // just for testing 
if($services)
    $methods = $services->getMethods($service_id);
while($method = @mysql_fetch_object($methods)) 
        print "<div id=\"selector_row\" onclick=\"loadFormForFunction(".$method->method_id.")\"><p id=\"selector_value\">".$method->method_name."</p></div>";
if($methods) mysql_free_result($methods);
unset($services);
?>
