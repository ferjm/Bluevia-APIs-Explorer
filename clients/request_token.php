<?php
include_once 'constants.php';

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
$oauth = $unica->getApiOAuth();

$consumer_key = $_GET['consumer_key'];
$consumer_secret = $_GET['consumer_secret'];
try {
    $request_token = $oauth->get_request_token($consumer_key,$consumer_secret);
    echo '<p>Request Token: '.$request_token->oauth_token.'</p>'; 
    echo '<p>Request Token Secret: '.$request_token->oauth_token_secret.'</p>';    
    echo '<p>Request: '.$unica->getLastRequest().'</p>';
    echo '<p>Response: '.$unica->getLastResponse().'</p>';   
} catch(Exception $e) {
    echo '<p>'.$e->getMessage().'</p>';
}

?>
