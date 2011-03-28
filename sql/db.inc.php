<?php
include_once('db.class.php');
$contents = file_get_contents("../sql/db.nfo");
list($user, $pass) = explode(',',$contents);
$db = new db_class();
$db_link = $db->connect('slge585.piensasolutions.com',$user,$pass,'qht253');
?>
