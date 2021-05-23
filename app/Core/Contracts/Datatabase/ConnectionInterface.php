<?php

namespace App\Core\Contracts\Database;

use Closure;

interface ConnectionInterface
{
    public function table(string $table);

    public function transaction(Closure $callback, int $attempts = 1);

    public function beginTransaction();

    public function commit();

    public function rollBack();

}