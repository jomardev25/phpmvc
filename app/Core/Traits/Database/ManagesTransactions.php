<?php

namespace App\Core\Traits\Database;

use Closure;
use Throwable;
use PDOException;

trait ManagesTransactions
{
    public function transaction(Closure $callback, $attempts = 1)
    {
        $callback = Closure::bind($callback, $this);
        try {

            $this->beginTransaction();
            $result = $callback();
            $this->commit();
            return $result;

        } catch (PDOException $e) {
            $this->rollback();
            throw new PDOException($e->getMessage());
        } catch (Throwable $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function beginTransaction()
    {
        $this->createTransaction();
    }

    protected function createTransaction()
    {
        $this->getPdo()->beginTransaction();
    }

    public function commit()
    {
        $this->getPdo()->commit();
    }

    public function rollBack()
    {
        $this->getPdo()->rollBack();
    }
}