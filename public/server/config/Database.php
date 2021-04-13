<?php
    class Database {
        // DB parameters
        private $host = "localhost";
        private $db_name = "media-manager";
        private $username = "root";
        private $password = "";
        private $connection;

        // DB Connect
        public function connect() {
            // Start session
            session_start();  

            if (isset($_SESSION["host"]) && isset($_SESSION["username"]) && isset($_SESSION["password"])) {
                $this->host = $_SESSION["host"];
                $this->username = $_SESSION["username"];
                $this->password = $_SESSION["password"];
            }
            $this->connection = null;
            try {
                $this->connection = new PDO(
                    'mysql:host=' . $this->host . ';dbname=' . $this->db_name, 
                    $this->username, $this->password
                );
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo 'Connection Error: ' . $e->getMessage();
            }
            return $this->connection;
        }
    }