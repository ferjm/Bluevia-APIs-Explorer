<?php
include_once '../constants.php';
include_once 'common.php';

$number =$_GET['address']; 
$sms_message = $_GET['message'];
$sms->setRecipient($number)
    ->setFrom(TOKEN)
    ->setMessage(SMS_SPECIAL_KEYWORD .' '. $sms_message);

try {   
    $id = $sms->send();    
	if (!empty($id)) 
	    echo '<p style="color:green"> SEND OK! SendSMS ID is: ' . $id . '</p>';
    echo '<p style="color:blue"> Request: ' . $unica->getLastRequest() .'</p>';
    echo '<p style="color:green"> Response: ' . $unica->getLastResponse() .'</p>';
} catch(Exception $e) {
    echo '<p style="color:red"> ERROR! ' . $e->getMessage() . '</p>';
}
?>
