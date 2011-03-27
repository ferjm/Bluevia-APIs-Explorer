<?php
/**
 *
 * @category    opentel
 * @package     com.bluevia
 * @copyright   Copyright (c) 2010 Telefónica I+D (http://www.tid.es)
 * @author      Bluevia <support@bluevia.com>
 * @version     1.0
 *
 * BlueVia is a global iniciative of Telefonica delivered by Movistar and O2.
 * Please, check out http://www.bluevia.com and if you need more information
 * contact us at support@bluevia.com
 */

/**
 * Include Zend Oauth Library: warning needs to be in include_path!
 * @see http://oauth.net
 * @see http://framework.zend.com/
 */
include_once 'Zend/Oauth/Consumer.php';

/**
 * oAuth Helper class
 */
class Unica_Api_Oauth extends Unica_Api_Abstract
{

    /**
    * Helper to determine current class' API Name
    * @return <string> API Name
    */
    protected function getAPIName() {
        return 'Oauth';
    }


    /**
     * Obtain request token
     * @param  <string>  $consumer_key    Consumer Key
     * @param  <string>  $consumer_secret Consumer Secret
     * @param  <string>  $callback_url Current Application URL
     * @param  <boolean> $auto_redirect Creates token cookie
     *                               and redirects automatically to oAuth
     *                               portal (Unica_Api_Constants::$oauth_url)
     * @return Zend_Oauth_Token_Request     
     * @throws Zend_Oauth_Exception,
     * Unica_Exception_Parameters,
     * Unica_Exception_Response
     */
    public function get_request_token(
            $consumer_key,
            $consumer_secret,
            $callback_url = null,
            $auto_redirect = true) {
        
        // check required parameters
        $this->checkParameter(
                array($consumer_key, $consumer_secret),
                "Consumer Key and Consumer Secret cannot be null."
                );

        // Obtain array of options, structured for Zend oAuth Library
        $oauthOptions = self::_get_options(
                $consumer_key,
                $consumer_secret,
                $callback_url
                );

        // get oAuth consumer object

        $this->_oauth = new Zend_Oauth_Consumer($oauthOptions);
        $oauth = $this->_oauth;
        // configure certificates
        $client = $oauth->getHttpClient();
        
        $this->_setClientConfig($client);
        $oauth->setHttpClient($client);
  
       
        // obtain request Token.
        // Note: endpoint needs non-empty body message, this is a workarround
        $token = $oauth->getRequestToken(array('foo'=>'bar')); 
               
        
        // redirect to oAuth Portal, allowing the user to authorize app
        if ($auto_redirect && !empty($callback_url)) {
            // store token: we will need later for getAccessToken Method
            // Cookie support is required!
            setcookie('req_token', serialize($token), null, '/');
            $oauth->redirect();
            
        } else {
            return $token;
        }
    }

    /**
     * Get Access Token
     * @param <string> $oauth_verifier The code returned by oAuth Application
     * @param <string> $consumer_key The Application identifier
     * @param <string> $consumer_secret The application identifier secret
     * @param <string> $request_token If application did manual redirection, the
     * request <token> object must be provided here @see get_request_token
     * @return <array> ('REQUEST_TOKEN', 'ACCESS_TOKEN')
     * @throws Unica_Exception_Parameters
     */
    public function get_access_token(
            $oauth_verifier,
            $consumer_key,
            $consumer_secret,
            $request_token = null) {
        
         // get request token from parameters or from cookie: @see get_request_token
        if (empty($request_token)) {
            $request_token = unserialize($_COOKIE['req_token']);
        }
        
        // check required parameters
        $this->checkParameter(
                array($oauth_verifier, $consumer_key, $consumer_secret),
                "OAuth Verifier and Consumer parameters cannot be null."
                );
                
        // Obtain array of options, structured for Zend oAuth Library
        $oauthOptions = self::_get_options($consumer_key, $consumer_secret);

        // get oAuth consumer object
        $this->_oauth = new Zend_Oauth_Consumer($oauthOptions);
        $oauth = $this->_oauth;

        // configure certificates
        $client = $oauth->getHttpClient();
        $this->_setClientConfig($client);
        $oauth->setHttpClient($client);
        
        // if already empty...
        if (empty($request_token)) {
            throw new Unica_Exception_Parameters('ERROR! Request token cookie
                is missing, please call get_request_token before asking
                for access token'
                    );
        }

        // request user Access Token
        $access_token = $oauth->getAccessToken($_GET,
                                $request_token
                        );

        // is responsability of the application to mantain Token + Token Secret
        return array(
            'REQUEST_TOKEN'=>$request_token,
            'ACCESS_TOKEN'=>$access_token,
            'HTTP_CLIENT'=>$oauth->getHttpClient()
                );
    }
   

    /**
     * Return constants for oAuth
     * @return <type>
     */
    protected function _get_options($consumerKey, $consumerSecret, $callbackUrl = null) {
        $options =  array(
            'consumerKey'=> $consumerKey,
            'consumerSecret'=> $consumerSecret,            
            'siteUrl'       => Unica_Api_Constants::$oauth_url,
            'accessTokenUrl'=> $this->_unica->composeUrl('/Oauth/getAccessToken'),
            'requestTokenUrl'=> $this->_unica->composeUrl('/Oauth/getRequestToken'),
            'signatureMethod' => 'HMAC-SHA1',
        );
        // add callback url if present to requests
        if (!empty($callbackUrl)) {
            $options['callbackUrl'] = $callbackUrl;
        }

        return $options;
    }

    /**
     * Returns a valid HTTP Client to be used with APIs integrated among oAuth     
     * @param <array> $application_context user{token_access, token_secret},
     *                                     app{consumer_key, consumer_secret}
     * @return <Zend_Http_Client_Adapter_Curl> Zend HTTP Client object
     */
    public function get_http_client($application_context) {
        $token = new Zend_Oauth_Token_Access();


        $oauthOptions = self::_get_options(
                $application_context['app']['consumer_key'],
                $application_context['app']['consumer_secret']
        );

        $token->setToken($application_context['user']['token_access']);
        $token->setTokenSecret($application_context['user']['token_secret']);
        
        // no need for these urls, save some memory
        unset($oauthOptions['accessTokenUrl']);
        unset($oauthOptions['requestTokenUrl']);

        // obtain HTTP Client, with oAuth header always integrated

        
        $client = $token->getHttpClient($oauthOptions);

        $client->setAdapter('Zend_Http_Client_Adapter_Curl');

        //***********ONLY if Curl support is enabled***************************
        $this->_setClientConfig($client);
                
        
        return $client;
    }

    function _setClientConfig(&$client) {
        $client->setAdapter('Zend_Http_Client_Adapter_Curl');
        $client->setConfig(
                array(
                    'curloptions' => array(
                      CURLOPT_CAINFO   => dirname(__FILE__) . DIRECTORY_SEPARATOR. "certificate.crt",
                      CURLOPT_SSL_VERIFYHOST => 2,
                      CURLOPT_SSL_VERIFYPEER => TRUE,
                      CURLOPT_FAILONERROR=> true,
                    )
                )
        );
        
    }
    
    /** @var Zend_Oauth_Consumer **/
    protected $_oauth = null;
}

