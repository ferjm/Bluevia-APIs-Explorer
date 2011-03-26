<?php
include_once('../db/db.inc.php');
$link=db::connect();
$query='select * from services';
if($result=mysql_query($query)) {
} else echo 'SQL error';
$mysql_free_result($result);
$mysql_close($link);
?>
