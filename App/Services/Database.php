<?php

class Database {
    private $connection;
    private static $instance;

    public function __construct() {
        try {
            $serverName = "garzasports-db-1";
            $database = "GARZASPORTS";
            $username = "sa";
            $password = "Fernad0101";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
    
            $this->connection = new PDO("sqlsrv:Server=$serverName;Database=$database",$username, $password, $options);

        } catch(PDOException $ex) {
            echo "Connection failed: ".$ex->getMessage();
            exit();
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        $this->connection = null;
    }
}