<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/core/env.core.php';


    class DBController 
    {
        private static $connection = null;
        private $port = 3306;

        public function __construct() {
            $hostname = Env::$env['DB_HOSTNAME'];
            $db_name = Env::$env['DB_NAME'];
            $username = Env::$env['DB_USERNAME'];
            $password = Env::$env['DB_PASSWORD'];

            try {
                if (DBController::$connection === null){
                    DBController::$connection = new PDO("mysql:host={$hostname};port={$this->port};dbname={$db_name}", $username, $password);
                }
                DBController::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        function __destruct(){
            $this->close();
        }

        public function close(){ 
            $this->connection = null;
        }

        public function getAutoIncrement($table)
        {
            $query = "SELECT `AUTO_INCREMENT` 
                    FROM  INFORMATION_SCHEMA.TABLES 
                    WHERE TABLE_SCHEMA = '{$this->db_name}' 
                    AND   TABLE_NAME   = '$table'";

            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row['AUTO_INCREMENT'];
        }

        public function getConnection() {
            return DBController::$connection;
        }
    }

?>