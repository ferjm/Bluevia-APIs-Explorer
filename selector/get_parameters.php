<?php
include_once('services.php');
$services = new Services();
$method_id = $_GET['method_id']; 
if($services)
    $params = $services->getParameters($method_id);
echo "<div class=\"selector_content selection_form\" ><p id='selector_title'>".$method_id."</p><br />"; //TODO: add long name field to db
echo "<form id=\"API_form\" name=\"API_form\">";
while($param = @mysql_fetch_object($params)) 
    echo "<span id=\"selector_value\">".$param->param_name."</span> :  <input type=\"".$param->param_html_component."\" name=\"".$param->param_name."\" /><br />";
echo "</div><div class=\"btn_submit\"><input  type=\"submit\" value=\"Submit\" /></div>";
echo "</form>";    
echo "</div>";       
if($params) mysql_free_result($params);
unset($services);
?>
