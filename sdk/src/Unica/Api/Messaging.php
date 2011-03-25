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
 * Messaging Base Class 
 */
class Unica_Api_Messaging extends Unica_Api_Abstract {

    /**
     * Override of Constructor
     * @param Unica $unica 
     */
    public function __construct(Unica $unica)
    {
        $this->setFrom($unica->getAccessToken(), 'alias');
        parent::__construct($unica);
    }


    /**
     * Get Received Message + get Message Attachment
     * @param <int> $registration_id MO Registration Identifier
     * @param <int> $message_id message unique identifier
     * @param <int> $attachment_id attachment identifier
     * (corresponding to the specific message)
     * @return <array> An array of received messages
     * @throws Unica_Exception_Parameters, Unica_Exception_Response,
     */
    protected function get_received_message (
            $registration_id,
            $message_id = null,
            $attachment_id = null,
            $attachments = false) {

        // format parameters
        if (!empty($message_id)) {
            $message_id = $message_id;
            if (false === $attachments)  {
                $attachments = "attachments";
            } else {
                $attachments = "";
            }
        } else {
            $message_id = "";
            $attachments = "";
        }

        $apiName = $this->_apiName;

        $url = "/".$apiName ."%ENV%/inbound/$registration_id/messages";

        // if message id is present, then ask for specific message Id
        if (!empty($message_id)) {
            $url .= "/" . $message_id;
        }
        // if attachment id is present, then ask for specific attachment id
        if (!empty($attachment_id)) {
            $url .= "/attachments/" . $attachment_id;
        }
        
        $response = $this->_unica->doRequest(
                    'GET',
                    $url,
                    null
                );

        $this->checkResponse($response);

        return $response;

    }

    /**
     * Get the message delivery status
     *
     * @param string $ident Message identification
     * @return array of status objects
     * @throws Unica_Exception_Parameters, Unica_Exception_Response,
     * Unica_Exception
     */
    public function get_delivery_status($ident)
    {
        // check for required parameters
        $this->checkParameter($ident,
            "Message Id cannot be null");
        $apiName = $this->_apiName;

        // format URL with parameters
        $url = '/'.$apiName .'%ENV%/outbound/requests/' . $ident .
            '/deliverystatus';

        // do server request
        $response = $this->_unica->doRequest('GET', $url, null);

        // check response for error Codes
        $this->checkResponse($response);
        // return response
        return $response;
    }


    // Common SETTERS & GETTERS

    /**
     * Remove  messaging parameters
     */
    public function reset()
    {
        $this->_from = null;
        $this->_to = array();
        $this->_message = null;
        return $this;
    }

    /**
     * Internal use function to store available params into a structure     
     * @param <array> &$structure pass-by-reference position of structure
     * @param <string> $reference reference value
     * @param <string> $correlator correlator value
     */
    protected function set_common_values( &$structure,
                                            $endpoint = null,
                                            $correlator = null,
                                            $criteria = null) {
        // if endpoint is present
        if (!empty($endpoint)) {
            $structure['reference']['endpoint'] =  $endpoint;
        }
        
        // if Message correlator is present
        if (!empty($correlator)) {
            $structure['reference']['correlator'] = $correlator;
        }

        // if filtering criteria is present...
        if (!empty($criteria)) {
            $structure['criteria'] = $criteria;
        }
    }

    /**
     * Defines 'From' number/alias for message (sms)
     * @param <string> $from User Access Token or Phonenumber
     *          IMPORTANT: you can't test your application in Sandbox mode
     *          if you specify a 'From' phoneNumber and you don't have msisdn.     
     * @param <string> $type (alias, phoneNumber, ...).
     *
     */
    public function setFrom($from, $type = 'alias') 
    {
        if (!empty($from) && $from !== "") {
            $this->_from = array($type => $from);
        }
                
        return $this;
    }

    /**
     * Defines a unique recipient for message
     * @param <string> $to  Destination (Hash or Phonenumber)
     * @param <string> $type (phoneNumber, alias, ...)
     */
    public function setRecipient($to, $type = 'phoneNumber')
    {
        $this->_to = array();
        if (!empty($to)) {
            $this->addRecipient($to, $type);
        }
        return $this;
    }

    /**
     * Adds a new recipient for message
     * @param <string> $to  Destination (Hash or Phonenumber)
     * @param <string> $type (phoneNumber, alias, ...)
     */
    public function addRecipient($to, $type = 'phoneNumber')
    {
        $this->_to[] = array($type => $to);
        return $this;
    }

    /**
     * Defines the message body
     * @param <string> $msg  Message Body
     */
    public function setMessage($msg)
    {
        $this->_message = $msg;
        return $this;
    }
   

    /**
    * Helper to determine current class' API Name
    * @return <string> API Name
    */
    protected function getAPIName() {
        throw new Unica_Exception_Parameters(
                        "Wrong call to abstract method getAPIName");
    }

    /**
     * Abstract Helper to return notification type
     * @return <array>
     */
    protected function get_delivery_receipt_notification_type(
            $endpoint,
            $correlator,
            $filterCriteria = null) {
        
        throw new Unica_Exception_Parameters(
                        "Wrong call to abstract method
                            get_delivery_receipt_notification_type");
    }

    // internal variables
    protected $_from;
    protected $_to = array();
    protected $_message;    
    protected $_files = array();
}
