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
 * Messaging class for SMS
 */
class Unica_Api_SMS extends Unica_Api_Messaging
{

    /**
    * Helper to determine current class' API Name
    * @return <string> API Name
    */
    protected function getAPIName() {
        return 'SMS';
    }


    /**
     * Sends SMS Message
     * @return string   The send request identifier
     * @throws Unica_Exception_Parameters, Unica_Exception_Response,
     */
    public function send($endpoint = null, $correlator = null)
    {
        // check required parameters
        $this->checkParameter(
                array($this->_from, $this->_to, $this->_message),
                "Please, set 'from', 'message' and 'to'"
                );

       
        // construct JSON body
        $body = array(
            'smsText' => array(                
                'originAddress' => array(
                    $this->_from
                ),
                'address' => $this->_to,
                'message' => $this->_message,
            )
        );

       
        if (!empty($endpoint)) {
            $body['smsText']['receiptRequest']['endpoint'] =  $endpoint;
        }
        
        if (!empty($correlator)) {
            $body['smsText']['receiptRequest']['correlator'] = $correlator;
        }
        

        // do server Request
        $response = $this->_unica->doRequest(
                'POST',
                '/SMS%ENV%/outbound/requests',
                $body
                );

        // check response for error codes
        $this->checkResponse($response);
        
        // obtain message id from header location
        $location = $this->_unica->getLastResponse()->getHeader('Location');
        $ident = preg_replace(
                '@^.+/requests/([^/]+)/deliverystatus$@',
                '$1',
                $location
                );

        return $ident;
    }


    /**
     * Get (pooling method) received message by ID
     * @param <int> $registration_id
     * @return <object> Message
     * @throws Unica_Exception_Parameters, Unica_Exception_Response
     */
    public function get_received_sms($registration_id) {
        return parent::get_received_message($registration_id);
    }



    /** 
     * Helper to return sms Delivery Receipt Notification type
     * @param <string> $endpoint
     * @param <int> $correlator
     * @param <string> $filterCriteria
     * @return string 
     */
    protected function get_delivery_receipt_notification_type(
            $endpoint,
            $correlator,
            $filterCriteria = null) {
        
        // form body
        $notifType = array(
             'deliveryReceiptNotification' => array(
                    'reference'=>array(
                    
                    ),
                    'originAddress'=> $this->_from,
              )
         );

         // add optional values
         $this->set_common_values($notifType['deliveryReceiptNotification'], 
                                    $endpoint,
                                    $correlator,
                                    $filterCriteria
                 );
        
         
         return $notifType;
    }
}