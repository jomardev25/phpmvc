<?php

namespace App\Core\Exceptions;

use PDOException;
use App\Core\Utils\Str;

class QueryException extends PDOException
{
    protected $sql;
    protected $bindings;

    public function __construct($sql, array $bindings, $previous)
    {
        $this->sql = $sql;
        $this->bindings = $bindings;
        $this->code = $previous->getCode();
        $this->message = $this->formatMessage($sql, $bindings, $previous);
    }

    protected function formatMessage($sql, $bindings, $previous)
    {
        return $previous->getMessage()." (SQL: ".Str::replaceArray("?", $bindings, $sql).")";
    }

    public function getSql()
    {
        return $this->sql;
    }

    public function getBindings()
    {
        return $this->bindings;
    }
}

