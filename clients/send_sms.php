<?php
include_once 'constants.php';

$application_context = array(
    'user' => array(
        'token_access' => TOKEN,
        'token_secret' => TOKEN_SECRET,
    ),
    'app' => array(
        'consumer_key' => CONSUMER_KEY,
        'consumer_secret' => CONSUMER_SECRET        
    )
);

$unica = new Unica($application_context);
$sms = $unica->getApiSms();

$number = $_GET['sms_address']; 
$sms_message = $_GET['sms_message'];
$sms->setRecipient($number)
    ->setMessage(SMS_SPECIAL_KEYWORD . $sms_message);

try {   
    $id = $sms->send();    
	if (!empty($id)) 
	    echo '<p style="color:green"> SEND OK! SendSMS ID is: ' . $id . '</p>';
    echo '<p style="color:blue"> Request: ' . $unica->getLastRequest() .'</p>';
    echo '<p style="color:blue"> Response: ' . $unica->getLastResponse() .'</p>';
} catch(Exception $e) {
    echo '<p style="color:red"> ERROR! ' . $e->getMessage() . '</p>';
}
?>
