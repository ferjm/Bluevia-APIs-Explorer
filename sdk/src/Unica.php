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
 * 
 */


/**
 * Include full API Package files
 */
include_once 'Unica/Exception.php';
include_once 'Unica/Api/Constants.php';
include_once 'Unica/Api/Abstract.php';
include_once 'Unica/Api/Messaging.php';
include_once 'Unica/Api/Sms.php';
include_once 'Unica/Api/Directory.php';
include_once 'Unica/Api/Oauth.php';
include_once 'Unica/Api/Advertising.php';
/**
 * Include extra exception types
 */
include_once 'Unica/Exception/Parameters.php';
include_once 'Unica/Exception/Response.php';
include_once 'Unica/Exception/Client.php';
include_once 'Unica/Exception/Server.php';


/**
 * Entry point for SDK access. Use getApi Methods to retrieve the api instances
 */
class Unica implements ArrayAccess
{
    /** @var Zend_Http_Client */
    protected $_http = null;
    protected $_options = array();
    protected $_access_token = null;

    /** @var Zend_Http_Response */
    protected $_lastResponse = null;
    
    /** @var public Zend_Http_Reply for Bluevia API explorer */ 
    private $_lastRequest = null;
    
    /** @var Zend_Log */
    static protected $_log = null;

    /**
     *
     * @param <array> $application_context user{token_access, token_secret},
     *                                     app{consumer_key, consumer_secret}          
     * @param <string> $log Zend Log instance
     */
    public function  __construct($application_context = null, $log = null)
    {
        if (!empty($application_context)) {
            /*@var Unica_Api_Oauth $oauth*/
            $oauth = $this->getApi('Oauth');
            $this->_http = $oauth->get_http_client($application_context);

            if (!empty($application_context['user']['token_access'])) {
                $this->_access_token = $application_context['user']['token_access'];
            }            
        } else {
            $this->_http = null;
        }		
        $this->_options['environment'] = Unica_Api_Constants::$environment;
        $this->_options['baseUrl'] = Unica_Api_Constants::$base_url;
                
        //$this->setOptions($options);
    }

    /**
     * If Log instance is present, writes to log file
     * @param <string> "info" or "err"
     * @param <string> $message
     */
    protected function watchdog($type, $message) {
        if(!empty($_log))  {
            switch($type) {
                case 'info':
                    self::$_log->info($message);                    
                    break;
                case 'err':
                    self::$_log->err($message);
                    break;
            }
            return true;
        } else {
            // log is not enabled
            return false;
        }

    }
      
    /**
     * Helper for APIS obtaining access token without the need of 
     * asking it to user
     */
    public function getAccessToken() {        
        return $this->_access_token;
    }

    public function getHttpClient()
    {
        return $this->_http;
    }

    /**
     * Sets the http client 
     * @param <Zend_Http_Client> $http
     */
    public function setHttpClient($http)
    {
        $this->_http = $http;
    }

    /**
     * Sets base Url (if different from connect.bluevia.com)
     * @param <string> $url
     */
    public function setBaseUrl($url)
    {
        $this->_options['baseUrl'] = $url;
    }

    /**
     * Generic function to obtain an API By name
     * @param <string> $name Unica_Api_XXX
     * @return instance of Unica_Api_XXX object
     */
    public function getApi($name)
    {
        $class = 'Unica_Api_' . ucfirst(strtolower($name));
        return new $class($this);
    }


    /**
     * Obtain SMS API instance
     * @return Unica_Api_Sms
     */
    public function getApiSms()
    {
        return new Unica_API_SMS($this);
    }

   
     /**
      * Obtain Advertising API instance
      * @return Unica_Api_Advertising
      */
    public function getApiAdvertising()
    {
        return new Unica_API_Advertising($this);
    }


     /**
      * Obtain Directory API instance
      * @return Unica_Api_Advertising
      */
    public function getApiDirectory()
    {
        return new Unica_API_Directory($this);
    }

    /**
     * Obtain oAuth API instance
     * @return Unica_API_Oauth
     */
    public function getApiOauth()
    {
    	$this->watchdog('info', 'getApiOAuth');
        return new Unica_API_Oauth($this);
    }


    /**
     * Obtain last response object
     * @ignore
     * @return <Zend_Http_Response>
     */
    public function getLastResponse()
    {
        return $this->_lastResponse;
    }
    
    /**
     * Bluevia APIs Explorer 
     */
    public function getLastRequest() 
    {
    	return $this->_lastRequest;	
    }
    
    /**
     * Replaces the %ENV% token by the selected (Sandbox, Commercial)
     * @ignore
     * @return <string>
     */
    protected function _rewriteUrl($url)
    {
        return str_replace('%ENV%', $this['environment'], $url);
    }

    public function composeUrl($url, $add_env = false)
    {
        if ($add_env) {
            $url = $this->_rewriteUrl($url);
        }
        
        $url = rtrim($this['baseUrl'], '/') . $url;
        
        return $url;
    }


    /**
     * 
     * doRequest: for internal usage     
     * @param <string> $method GET/POST/DELETE
     * @param <string> $url    Relative URL
     * @param <array|object|string> $body   Body Data
     * @param <array> $files  Attached files
     * @param <string> $encoding encoding
     * @param <string> $query Query params
     * @return HTTP Message (200, ...)
     */
    public function doRequest($method, $url, $body = '', $files = array(), $encoding = 'application/json', $query = null)
    {
        // take mms body and remove from json input if present        
        if (is_array($body) && !empty($body['mms_body'])) {
            $mms_body = $body['mms_body'];
            unset($body['mms_body']);
        }

        if (is_array($body)) {
            $msg_body = json_encode($body);
        } else {
            $msg_body = $body;
        }

        $this->watchdog('info', 'Request: ' . $msg_body);
        $this->watchdog('info', 'URL: ' . $url);

        $url = $this->_rewriteUrl($url);
        $url = rtrim($this['baseUrl'], '/') . $url;

        $client = $this->getHttpClient();

        $client->setUri($url);
        $client->setMethod($method);        

        if ($encoding === 'application/json') {
            $client->setParameterGet('alt', 'json');
        }
        
        $client->setParameterGet('version', 'v1');
    
        // file attachments
        if (!empty($mms_body)) {
            $client->setFileUpload('message', 'contents', $msg_body, 'application/json');
            // mms body inclusion as attachment
            $client->setFileUpload('textBody.txt', 'body', $mms_body, 'text/plain');            

            foreach ($files as $idx=>$file) {
                $client->setFileUpload($file, $idx);
            }
        } else {
            
            $client->setRawData($msg_body, $encoding);
        }
        
        

        if (!empty($query) && is_array($query)) {
            foreach ($query as $queryParam => $value) {
                $client->setParameterGet($queryParam, $value);
            }
        }
        try {        	
        	$this->_lastResponse = $client->request();
        	$this->_lastRequest = $client->getLastRequest();
	        $body = json_decode($this->_lastResponse->getBody());
	        
	        // if no success on json_decode,
	        if (empty($body))  {
	            $body = $this->_lastResponse->getBody();
	        }
	
	        if (is_object($body) && $this->_lastResponse->isError()) {            
	            if (!empty($body->ClientException) && is_object($body->ClientException)) {
	                throw new Unica_Exception_Client($body->ClientException->text, $body->ClientException->exceptionId);
	            } else if (!empty($body->ServerException) && is_object($body->ServerException)) {
	                throw new Unica_Exception_Server($body->ServerException->text, $body->ServerException->exceptionId);
	            }
	        }
	        // log response if logging is enabled
	        $this->watchdog('info', 'Raw response: ' . print_r($body,1));               	
        } catch(Exception $e) {        	        	
        	$body = null;
        }        
        return $body;
    }

    public function getUrlEncoded($params) {
        // Get first query param        
        $queryparams = '';
        foreach($params as $key=>$value) {
            $queryparams .= '&'.rawurlencode($key) . '=' . rawurlencode($value);
        }
        $queryparams = /*'?' .*/ substr($queryparams, 1);

        return $queryparams;
    }

    

    // Implements ArrayAccess interface
    
    public function offsetExists($offset)
    {
        return isset($this->_options[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->_options[$offset]) ? $this->_options[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->_options[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->_options[$offset]);
    }
}