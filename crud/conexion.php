<?php 
require 'config.php';
class dao{
    

    function __construct() {
        // connection settings
        $this->$db_host=HOST;
        $this->$db_user=USER_NAME;
        $this->$db_pass=PASS;
        $this->$db_name=DB_NAME;

    }

    function __destruct() {
        // close connections when the object is destroyed
        $this->dbh = null;
    } 
    
    function db_connect() {
        try { 
            $db = mysqli_connect($this->db_host, $this->db_user,  
                    $this->db_pass, $this->db_name);

            return $db;

        } catch (PDOException $e) {
            // eventually write this to a file
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

    } // end db_connect()'

}

?>