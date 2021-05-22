<?php

namespace App\Core;

use PDO;

abstract class Database extends PDO
{
    private $dbHost;
    private $dbType;
    private $dbName;
    private $dbUser;
    private $dbPass;

    public function __construct()
    {
        //$this->dbHost = config("database.default.")
        //parent::__construct($DBTYPE.":host=".$DBHOST.";dbname=".$DBNAME, $DBUSER, $DBPASS);
        //parent::setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    
}