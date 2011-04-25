<?php
include_once '../constants.php';
include_once 'common.php';

$id = $_GET['ident'];
try {
    $delivery_status = $sms->get_delivery_status($id);
    echo '<p style="color:blue"> Request: ' . $unica->getLastRequest() .'</p>';
    echo '<p style="color:green"> Response: ' . $unica->getLastResponse() .'</p>';
} catch(Exception $e) {
    echo '<p style="color:red"> ERROR> '. $e->getMessage() . '</p>';
}
?>
