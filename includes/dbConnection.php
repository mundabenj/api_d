<?php
    class dbConnection{
        private $connection;

        private $db_type;
        private $db_host;
        private $db_port;
        private $db_user;
        private $db_pass;
        private $db_name;

        public function __construct($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name){
            $this->db_type = $db_type;
            $this->db_host = $db_host;
            $this->db_user = $db_user;
            $this->db_pass = $db_pass;
            $this->db_name = $db_name;
            
            $this->connection($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name);
        }
        public function connection($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name){
            switch($db_type){
                case 'PDO' :
                    if($db_port<>Null){
                        $db_host .= ":" . $db_port;
                    }
                    try {
                        // Create the connection
                        $this->connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
                        // set the PDO error mode to exception
                        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        // echo "Connected successfully :-)";
                      } catch(PDOException $e) { return "Connection failed: " . $e->getMessage(); }
                      break;
                case 'MySQLi' :
                    if($db_port<>Null){
                        $db_host .= ":" . $db_port;
                    }
                    // Create connection
                    $this->connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
                    // Check connection
                    if ($this->connection->connect_error) { return "Connection failed: " . $this->connection->connect_error; } else{ echo "Connected successfully"; }
                    break;
            }
        }
/**************************************************************************************************
 * Insert Query Method
 ***************************************************************************************************/
        public function insert($table, $data){
            ksort($data);
            $fieldDetails = NULL;
            $fieldNames = implode('`, `', array_keys($data));
            $fieldValues = implode("', '", array_values($data));
            $sth = "INSERT INTO $table (`$fieldNames`) VALUES ('$fieldValues')";
            
            // use exec() because no results are returned
            switch($this->db_type){
                case 'PDO' :
                    try{
                    $this->connection->exec($sth);
                        return TRUE;
                    } catch(PDOException $e) {
                        return $sth . "<br>" . $e->getMessage();
                    }
                    break;
                case 'MySQLi' :
                    if ($this->connection->query($sth) === TRUE) {
                        return TRUE;
                    } else {
                        return "Error: " . $sth . "<br>" . $this->connection->error;
                    }
                    break;
            }
        }

    }