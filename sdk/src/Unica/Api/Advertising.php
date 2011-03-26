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
 * Advertising API
 */
class Unica_Api_Advertising extends Unica_Api_Abstract {

    /**
     * Helper to determine current class' API Name
     * @return <string> API Name
     */
    protected function getAPIName() {
        return 'Advertising';
    }

    /**
     * Fetch an AD
     * @param <array> $params array(
     *                      user_agent (R),
     *                      ad_request_id (R),
     *                      ad_space(R),
     *                      max_ads (O),
     *                      protection_policy (O),
     *                      ad_presentation (O)
     *                      ad_presentation_size (O)
     *                      keyword (O)
     * )
     * @param <bool> $return_asoc If true, parses xml with XMLReader class
     * @throws Unica_Exception_Parameters, Unica_Exception_Response
     * @return Ad Object
     */
    public function request($params, $return_asoc = true) {
        // check required parameters
        $this->checkParameter(
                array(
                    $params['user_agent'],
                    $params['ad_request_id'],
                    $params['ad_space'],
                ),
                "The Parameters user_agent, ad_request_id, ad_space cannot be null"
        );
        // extra 'null' checkings
        foreach ($params as $key => $value) {
            switch ($key) {
                case 'ad_presentation':
                    $this->_check_value($key, $value);
                    break;
                case 'ad_presentation_size':
                    $this->_check_value($key, $value);
                    break;
                case 'keyword':
                    $this->_check_value($key, $value);
                    break;
            }
        }


        // FORM URL ENCODE Query Params        
        $queryparams = $this->_unica->getUrlEncoded($params);

        // obtain API Name
        $apiName = $this->_apiName;

        // do Request
        $response = $this->_unica->doRequest(
                        'POST',
                        "/" . $apiName . "%ENV%/simple/requests",
                        $queryparams,
                        null,
                        "application/x-www-form-urlencoded"
        );

        // check response Error Codes
        $this->checkResponse($response);

        // return XML Response
        if (!empty($response) && $return_asoc) {            
            $xmlr = new XMLReader();
            $xmlr->XML($response);
            //$response = $this->_xml2assoc($xmlr, "root");
            $response = $this->_simplify_ad($xmlr);
            $xmlr->close();
        }

        return $response;
    }

    protected function _check_value($key, $value) {
        if ($value === null || $value === '') {
            throw new Unica_Exception_Parameters(
                    'a valid request is expected for ' . $key);
        }
    }

    protected function _xml2assoc($xml, $name) {

        $tree = null;

        while ($xml->read()) {
            if ($xml->nodeType == XMLReader::END_ELEMENT) {
                return $tree;
            } else if ($xml->nodeType == XMLReader::ELEMENT) {
                $node = array();

                $node['tag'] = $xml->name;

                if ($xml->hasAttributes) {
                    $attributes = array();
                    while ($xml->moveToNextAttribute()) {
                        $attributes[$xml->name] = $xml->value;
                    }
                    $node['attr'] = $attributes;
                }

                if (!$xml->isEmptyElement) {
                    $childs = $this->_xml2assoc($xml, $node['tag']);
                    $node['childs'] = $childs;
                }
                $tree[] = $node;
            } else if ($xml->nodeType == XMLReader::TEXT) {
                $node = array();
                $node['text'] = $xml->value;
                $tree[] = $node;
            }
        }

        return $tree;
    }

    /**
     * Simplifies Advertising XML
     * @param <XMLReader> $xml
     * @return array of creative elements
     */
    protected function _simplify_ad(/*@var XMLReader */ $xml) {
        /* @var array */
        $creative_elements = array();
        /* @var array */
        $current_element = array();
        /* @var string */
        $ad_type = null;
        /* @var string type of presentation (e.g 0101) */
        $ad_presentation = null;
        $type_id = '';

        while ($xml->read()) {
                        
            switch(strtolower($xml->name)) {
                case 'tns:resource':
                    if ($xml->nodeType === XMLReader::ELEMENT) { // ensure it is start element
                        $ad_presentation = $xml->getAttribute('ad_presentation');
                        if(!empty($ad_presentation)) {
                            $type_id = $ad_presentation;
                        }                        
                    }
                    break;

                case 'tns:creative_element':                    
                    // reset row: new element reinitialize
                    if ($xml->nodeType === XMLReader::END_ELEMENT) {
                        if (!empty($current_element)) {
                            $creative_elements[]= $current_element;
                        }                    
                        $current_element = array();
                        $ad_type = null;
                        $ad_presentation = null;
                    } else {
                        $ad_type = $xml->getAttribute('type');
                        if(!empty($ad_type)) {
                            $current_element['type_name'] = $ad_type;
                            $current_element['type_id'] = $type_id; // always the same
                        }                        
                    }
                    break;
                    
                case 'tns:attribute':
                    if ($xml->nodeType === XMLReader::ELEMENT) { // ensure it is start element
                        $xml2 = new XMLReader();
                        if ($xml->hasAttributes) {
                            $type = trim($xml->getAttribute('type'));
                            switch($type) {
                                case 'adtext':
                                case 'locator':
                                    $xml->read();
                                    if ($xml->hasValue) {
                                        $current_element['value'] = $xml->value;
                                    }

                                    break;
                                case 'URL':
                                    $xml->read();
                                    if ($xml->hasValue) {
                                        $current_element['interaction'] = $xml->value;
                                    }

                                    break;

                            }                            
                        }
                    }
                    break;
               
                default:                    
                    break;
            }
             
            
        }
        return $creative_elements;
    }

}
