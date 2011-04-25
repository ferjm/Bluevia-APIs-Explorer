<?php
include_once '../constants.php';

$application_context = array( 
    'user' => array(
        'token_access' => TOKEN,
        'token_secret' => TOKEN_SECRET        
    ),
    'app' => array(
        'consumer_key' => CONSUMER_KEY,
        'consumer_secret' => CONSUMER_SECRET
    )
);

$unica = new Unica($application_context);
$sms = $unica->getApiSms();
?>
