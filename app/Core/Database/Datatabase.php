<?php

namespace App\Core\Database;

use PDO;
use PDOException;

class Datatabase
{
    private $connections = [];
    private $dbConfig = [];

    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    public function connection(string $connectionName = "default")
    {
        try {

            if (array_key_exists($connectionName, $this->connections))
                return $this->connections[$connectionName];
            
            $driver   = $this->dbConfig["driver"]; 
            $host     = $this->dbConfig["host"];
            $database = $this->dbConfig["database"];
            $port     = $this->dbConfig["port"];
            $username = $this->dbConfig["username"]; 
            $password = $this->dbConfig["password"];
            $charset  = $this->dbConfig["charset"];
            $options  = $this->dbConfig["options"];
            $dsn      = "{$driver}:host={$host};dbname={$database};port={$port};charset={$charset}";

            $pdo = new PDO($dsn, $username, $password, $options);
            $connection = new Connection($pdo);
            $this->connections[$connectionName] = $connection;

            return $connection;

        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    public function getConnection(string $connectionName = "default")
    {   
        $connection = null;
        if(isset($this->connections[$connectionName]))
            $connection = $this->connections[$connectionName];
        return $connection;
    }

    public function disconnect($connectionName = null)
    {
        $connection = $this->connections[$connectionName = $connectionName ? : $this->getDefaultConnection()];
        if (isset($connection)) {
            $this->connections[$connectionName]->disconnect();
        }
    }

    public function getDefaultConnection()
    {
        $config = include "../../../config/database.php";
        $connection = null;
        if(isset($config["default"]))
            $connection = $config["default"];

        return $connection;
    }

}