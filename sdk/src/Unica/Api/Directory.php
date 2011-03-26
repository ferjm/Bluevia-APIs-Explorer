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
 * Directory API
 */
class Unica_Api_Directory  extends Unica_Api_Abstract {

    // valid Directory methods:    
    const USER_PROFILE = 'UserProfile';
    const USER_ACCESS_INFO = 'UserAccessInfo';
    const USER_TERMINAL_INFO = 'UserTerminalInfo';



   // array of valid methods (@see USER_XXXX constants)
   protected $valid_methods = null;        

   /**
     * Constructs Directory API 
     * @param <Unica> $unica The UNICA API base instance
     */
   public function __construct($unica) {
       $this->valid_methods = array(                                
                                self::USER_PROFILE,
                                self::USER_ACCESS_INFO,
                                self::USER_TERMINAL_INFO
        );
       parent::__construct($unica);
   }

   /**
    * Helper to determine current class' API Name
    * @return <string> API Name
    */
    protected function getAPIName() {
        return 'Directory';
    }

    
    /**
     *  This method is in charge of retrieving user information
     * @param <string> $guid Global user identifier that identifies a specific user
     * @param <string> $guid_type Global user identifier that identifies a specific user
     *                  (alias, phoneNumber, telUri, sipUri,email, ipAddress, otherId)
     * @param <VALID_METHODS> $type Information to be retrieved. Can be one or more VALID_METHODS.
     *  If null, the whole user information is retrieved
     * @throws Unica_Exception_Parameters, Unica_Exception_Response
     * @return User Info Object
     */
    public function get_user_info(
            $guid,
            $guid_type = 'alias',
            $type = self::USER_PROFILE) {
        
        // check required parameters
        if($type !== null) {
            $checkedtype = $type;
        } else {
            $checkedtype = '<empty>';
        }

        $this->checkParameter(
                array($guid, $guid_type, $checkedtype),
                "Please, add parameters UserId, UserIdType and type"
                );


        // initialize values
        $_type = "";
        $_data_sets = "";

        // create URL depending on parameters
        if(!empty($type)) {
            if(is_array($type)) {                
                // verify types are one of the valid ones
                $this->verifyTypes($type);
                // if no exceptions
                $_type = "/UserIdentities";
                $_data_sets = implode(',', $type);
            } else if(is_string($type)) {                
                // verify types are one of the valid ones
                $this->verifyTypes($type);
                $_type = "/" . $type;                
            } else {
                throw new Unica_Exception_Parameters("Invalid type");
            }
        }
            
        // add optional parameters
        $params = array();
        if (!empty($_data_sets)) {
            $params['dataSets'] = $_data_sets;
        }

        // obtain real api name
        $apiName = $this->_apiName;

        // do SDK Request
        $response = $this->_unica->doRequest(
                'GET',
                "/".$apiName ."%ENV%/". $guid_type . ":" . $guid .
                    "/UserInfo" . $_type,
                $params);

        // check response for errorCodes
        $this->checkResponse($response);
        
        return $response;
        
    }


    /**
     * Helper function to verify type parameter
     * @param <array|string> $type
     * @throws Unica_Exception_Parameters, Unica_Exception_Response
     */
    protected function verifyTypes($type) {        
        if(!is_array($type)) {
            $type = array($type);
        }
        // check if it is on the types allowed
        foreach($type as $currentType) {
            if (!in_array($currentType, $this->valid_methods)) {
                throw new Unica_Exception_Parameters("Wrong type sent");
            }
         }        
    }

    
}

