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
 * Generic Base Class
 */
class Unica_Api_Abstract
{
    /** @var Unica */
    protected $_unica;
    /** @var string */
    protected $_apiName;


    /**
     *
     * @param Unica $unica The UNICA API base instance
     */
    public function __construct(Unica $unica)
    {
        $this->_unica = $unica;
        $this->_apiName = $this->getAPIName();
    }


     /**
     * Helper to Determine if last error code was an error (~20X)
     * @param $response The response object
     * @return The response code
     * @throws Unica_Exception_Response if response contains errorCode
     */
    protected function checkResponse($response) {
        // get Response status code
        $code = $this->_unica->getLastResponse()->getStatus();

        // if code != 2XX it is an error
        if ($code < 200 || $code > 299) {
            if (is_object($response)) {
                throw new Unica_Exception_Response (
                    "Error in response [".$code."]: ".print_r($response->body, 1)
                      );
            } else if (is_string($response)) {
              throw new Unica_Exception_Response (
                    "Error in response [".$code."]: ".htmlentities(print_r($response, 1)));
            }
        }

        return $code;
     }


    /**
     * Helper to determine if required parameter is empty
     * @param <string|array> $parameter_value. Parameter, or array of parameters.
     * @param <string> $error_string. Error displayed
     * @throws Unica_Exception_Parameters if missing parameters
     */
    protected function checkParameter($parameter_value, $error_string) {
        if (!is_array($parameter_value)) {
            $parameter_value = array($parameter_value);
        }

        foreach($parameter_value as $key=>$value) {
            if (empty($value) || $value === "") {
                throw new Unica_Exception_Parameters(
                        $error_string);
            } 
        }
    }

    /**
     * Abstract method to obtain Current API Name, used on common inheritance
     * @return <string> API Name
     */
    protected function getAPIName() {
        throw new Unica_Exception_Parameters(
                        "Wrong call to abstract method getAPIName");
    }
}