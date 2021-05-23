<?php

namespace App\Core\Traits\Database;

trait Aliases
{

    public function andWhere($column, $operator = null, $value = null): self
    {
        return $this->where($column, $operator, $value);
    }

    public function and($column, $operator = null, $value = null): self
    {
        return $this->where($column, $operator, $value);
    }

    public function or($column, $operator = null, $value = null): self
    {
        return $this->orWhere($column, $operator, $value);
    }

    public function whereIsNull(string $column): self 
    {
        return $this->whereNull($column);
    }

    public function andWhereIsNull(string $column): self 
    {
        return $this->whereNull($column);
    }

    public function andWhereNull(string $column): self 
    {
        return $this->whereNull($column);
    }        

    public function andIsNull(string $column): self
    {
        return $this->whereNull($column);
    }


    public function andNull(string $column): self
    {
        return $this->whereNull($column);
    }

    public function orIsNull(string $column): self
    {
        return $this->orWhereNull($column);
    }

    public function orWhereIsNull(string $column): self
    {
        return $this->orWhereNull($column);
    }

    public function orNull(string $column): self
    {
        return $this->orWhereNull($column);
    }

    public function whereIsNotNull(string $column): self 
    {
        return $this->whereNotNull($column);
    }

    public function andWhereIsNotNull(string $column): self 
    {
        return $this->whereNotNull($column);
    }

    public function andWhereNotNull(string $column): self 
    {
        return $this->whereNotNull($column);
    }

    public function andIsNotNull(string $column): self 
    {
        return $this->whereNotNull($column);
    }


    public function andNotNull(string $column): self
    {
        return $this->whereNotNull($column);
    }

    public function orIsNotNull($column): self
    {
        return $this->orWhereNotNull($column);
    }

    public function orNotNull($column): self
    {
        return $this->orWhereNotNull($column);
    }

    public function orWhereIsNotNull($column): self
    {
        return $this->orWhereNotNull($column);
    }

    public function andWhereIn(string $column, array $values): self 
    {
        return $this->whereIn($column, $values);
    }

    public function andIn(string $column, array $values): self 
    {
        return $this->whereIn($column, $values);
    }

    public function orIn(string $column, array $values): self 
    {
        return $this->orWhereIn($column, $values);
    }

    public function andNotIn(string $column, array $values): self 
    {
        return $this->whereNotIn($column, $values);
    }

    public function andWhereNotIn(string $column, array $values): self 
    {
        return $this->whereNotIn($column, $values);
    }

    public function orNotIn(string $column, array $values): self 
    {
        return $this->orWhereNotIn($column, $values);
    }

    public function andWhereBetween(string $column, array $values): self 
    {
        return $this->whereBetween($column, $values);
    }

    public function andBetween(string $column, array $values): self 
    {
        return $this->whereBetween($column, $values);
    }

    public function orBetween(string $column, array $values): self 
    {
        return $this->orWhereBetween($column, $values);
    }

    public function andWhereNotBetween(string $column, array $values): self 
    {
        return $this->whereNotBetween($column, $values);
    }

    public function andNotBetween(string $column, array $values): self 
    {
        return $this->whereNotBetween($column, $values);
    }

    public function orNotBetween(string $column, array $values): self 
    {
        return $this->orWhereNotBetween($column, $values);
    }
}