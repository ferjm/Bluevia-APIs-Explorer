<?php
    include_once('../sql/db.inc.php');
    include_once('constants.php');

    class Services {

        static public function getServices() {
            $link=db::connect();
            $query='select * from service';
            if($result=mysql_query($query)) {
            } else {
                    echo 'SQL error';
            }
            mysql_close($link);
            return $result;
        }

    }
?>
