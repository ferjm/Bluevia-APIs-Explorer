<?php

class db {
    static public function connect() {
        $link=mysql_connect('slge585.piensasolutions.com','','') or die('Database connection error');
        mysql_select_db('qht253',$link) or die('Database selection error');
        return $link;
    }
}

?>
