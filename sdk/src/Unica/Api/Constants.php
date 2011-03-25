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
 * API Constants
 */
class Unica_Api_Constants {
    // stores base service URL
    public static $base_url = "https://api.bluevia.com/services/REST";
        
    // Important: wrong application environment could provide 'Invalid consumer
    //  key error' or App [X] can not use Api [Y]
    
    // possible values: _Sandbox, or empty '' for Commercial
    public static $environment = '_Sandbox';
    
    
    // stores oAuth portal 
    public static $oauth_url = "http://connect.bluevia.com/authorise";
    

}

