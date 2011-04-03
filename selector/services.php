<?php

    include_once('constants.php');
    include_once('../sql/db.class.php');

    class Services {

        var $db;    //holds the services db instance
        var $link;  //holds the db link identifier

        function __construct() {
            $contents = file_get_contents("../sql/db.nfo");
            list($user, $pass) = explode(',',$contents);
            $pass = substr($pass,0,-1);            
            $this->db = new db_class();
            if(!$this->link = $this->db->connect('slge585.piensasolutions.com',$user,$pass,'qht253', false))
                return false;
        }

        function __destruct() {
            if($this->link) mysql_close($this->link);
        }

        function getServices() {
            if($this->db) {                
                if($result = $this->db->select('select * from service'))
                    return $result;
                else
                    $this->db->print_last_error();
            }
            return false;
        }

        function getMethods($service_id) {
            if($this->db) {                
                if($result = $this->db->select("select * from method where method_service_id=$service_id"))
                    return $result;
                else
                    $this->db->print_last_error();
            }
            return false;
        }

        function getParameters($method_id) {
            if($this->db) {                
                if($result = $this->db->select("select * from parameters where param_method_id=$method_id"))
                    return $result;
                else
                    $this->db->print_last_error();
            }
            return false;
        }
        
        function getMethodAction($method_id) {
        	if($this->db) {                
                if($result = $this->db->select("select method_action from method where method_id=$method_id"))
                    return $result;
                else
                    $this->db->print_last_error();
            }
            return false;
        }
        
    }
?>
