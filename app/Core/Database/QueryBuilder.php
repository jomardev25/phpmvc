<?php

namespace App\Core\Database;

use PDO;

class QueryBuilder
{
    private $pdo;
    private $query;
    private $statement;
    private $execute;
    private $lastInsertId;
    protected $fetchMode = PDO::FETCH_OBJ;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPdoConnection()
    {
        return $this->pdo;
    }

    public function query(string $query): self
    {
        $this->query = $query;
        $this->statement = $this->pdo->prepare($query);
        return $this;
    }

    public function values(array $values): self
    {
        foreach ($values as $key => $value) {
            
            if (!is_array($value)) {
                $this->statement->bindValue(":$key", $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            } else {
                foreach($value as $k => $v){
                    $this->statement->bindValue(":$k", $v, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
                }
            }
            
        }
        return $this;
    }

    public function execute()
    {
        try {
            $this->execute = $this->statement->execute();
            $this->lastInsertId = (int) $this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    public function exec(): bool
    {
        $this->execute();
        return $this->execute;
    }

    public function lastId(): int
    {
        $this->execute();
        return $this->lastInsertId;
    }

    public function count(): int
    {
        $this->execute();
        return (int) $this->statement->rowCount();
    }

    public function get()
    {
        $this->execute();
        return $this->statement->fetchAll($this->fetchMode);
    }

    public function all()
    {
        return $this->get();
    }

    public function first()
    {
        $this->execute();
        return $this->statement->fetch($this->fetchMode);
    }

    public function setFetchMode($fetchMode)
    {
        $this->fetchMode = $fetchMode;
        $this->statement->setFetchMode($this->fetchMode);
        return $this;
    }

    public function toSql(): string
    {
        ob_start();
        $this->statement->debugDumpParams();
        $output = ob_get_contents() ?: "";
        ob_end_clean();
        die("<pre>".htmlspecialchars($output)."</pre>");
    }

    public function __toString(): string
    {
        return $this->query;
    }
}