<?php
include_once '../constants.php';
include_once 'common.php';

$registration_id = '6780';//$_GET['registrationId'];

try{
    $response = $sms->get_received_sms($registration_id);
    echo '<p style="color:blue"> Request: ' . $unica->getLastRequest() .'</p>';
    echo '<p style="color:green"> Response: ' . $unica->getLastResponse() .'</p>';
} catch(Exception $e) {
   echo '<p style="color:red"> ERROR! ' . $e->getMessage() . '</p>';
}
?>
