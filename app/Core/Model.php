<?php

namespace App\Core;


abstract class Model extends Database
{
    private $dbHost;
    private $dbType;
    private $dbName;
    private $dbUser;
    private $dbPass;

}