<?php

class Database {
    private $connection;

    public function __construct() {
        try {
            $serverName = "garzasports-db-1";
            $database = "GARZASPORTS";
            $username = "sa";
            $password = "Fernad0101";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_SYSTEM
            ];
    
            $this->connection = new PDO("sqlsrv:Server=$serverName;Database=$database",$username, $password, $options);

        } catch(PDOException $ex) {
            echo "Connection failed: ".$ex->getMessage();
            exit();
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }

    public function closeConnection() {
        $this->connection = null;
    }
}