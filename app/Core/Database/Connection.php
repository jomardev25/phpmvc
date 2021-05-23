<?php

namespace App\Core\Database;

use PDO;
use App\Core\Traits\Database\ManagesTransactions;
//use App\Core\Contracts\Database\ConnectionInterface;

class Connection //implements ConnectionInterface
{
    use ManagesTransactions;

    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function query()
    {
        return new Builder($this->pdo);
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function disconnect()
    {
        $this->pdo = null;
    }

    public function table(string $table)
    {
        return $this->query()->from($table);
    }

}