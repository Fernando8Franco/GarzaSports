<?php

class Database {
    private $env;
    private $connection;

    public function __construct() {
        $this->env = parse_ini_file('.env');
        try {
            $serverName = $this->env['DB_HOST'];;
            $database = $this->env['DB_DATABASE'];;
            $username = $this->env['DB_USER'];;
            $password = $this->env['DB_PASSWORD'];

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