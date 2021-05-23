<?php

namespace App\Core\Database;

use PDO;
use InvalidArgumentException;
use App\Core\Traits\Database\Query;
use App\Core\Traits\Database\Aliases;

class Builder
{
    use Query, Aliases;

    protected $pdo;
    protected $table;
    protected $insert = [];
    protected $update = [];
    protected $select = ["*"];
    protected $delete = false;
    protected $join = [];
    protected $where = [];
    protected $groupBy = "";
    protected $orderBy = "";
    protected $limit;
    protected $offset = false;
    protected $whereOperators = [
        "=", 
        ">", 
        ">=", 
        "<", 
        "<=", 
        "!=", 
        "<>", 
        "LIKE",
        "NOT LIKE", 
        "IS NULL", 
        "IS NOT NULL", 
        "IN", 
        "NOT IN",
        "BETWEEN",
        "NOT BETWEEN",
        "REGEXP"
    ];

    protected $startParentheses = false;
    protected $endParentheses = false;

    public function __construct(PDO $pdo)
    {
        $this->pdo   = $pdo;
    }

    public function getPDOConnection()
    {
        return $this->pdo;
    }

    public function from($table) : self
    {
        $this->table = $table;
        return $this;
    }

    public function insert(array $data): QueryBuilder
    {
        if (empty($data))
            return true;

        $this->insert = $data;
        $query = $this->execute();
        return $query;
    }

    public function update(array $data)
    {
        $this->update = $data;
        $query = $this->execute();
        return $query;
    }

    public function delete(): QueryBuilder
    {
        $this->delete = true;
        $query = $this->execute();
        return $query;
    }

    public function first(array $attributes = ["*"])
    {
        $this->select = $attributes;
        $this->limit(1);
        $query = $this->execute();
        return $query->first();
    }

    public function select(...$attributes): QueryBuilder
    {
        $this->select = (!empty($attributes)) ? join(", ", $attributes) : "*";
        $query = $this->execute();
        return $query;
    }

    public function get(array $attributes = ["*"])
    {
        $this->select = $attributes;
        $query = $this->execute();
        return $query->get();
    }

    public function all(array $attributes = ["*"])
    {
        $this->select = $attributes;
        $query = $this->execute();
        return $query->all();
    }

    public function pluck($column)
    {
        $this->select = $column;
        $results = $this->execute()->get();
        $results = array_map(function($item) use($column){
            return $item->{$column};
        }, $results);
        return $results;
    }

    public function toSql(): string
    {
        $this->select = ["*"];
        $query = $this->execute();
        return $query->toSql();
    }

    public function count(string $attribute): int
    {
        $this->select = $attribute;
        $query = $this->execute();
        return $query->count();
    }

    public function max(string $attribute) 
    {
        $max = "MAX(".$attribute.")";
        $this->select = $max;
        $query = $this->execute();
        return $query->get()->{$max};
    }

    public function min(string $attribute) 
    {
        $min = "MIN(".$attribute.")";
        $this->select = $min;
        $query = $this->execute();
        return $query->get()->{$min};
    }

    public function avg(string $attribute) 
    {
        $avg = "AVG(".$attribute.")";
        $this->select = $avg;
        $query = $this->execute();
        return $query->get()->{$avg};
    }

    public function sum(string $attribute) 
    {
        $sum = "SUM(".$attribute.")";
        $this->select = $sum;
        $query = $this->execute();
        return $query->get()->{$sum};
    }

    public function leftJoin(string $join): self
    {
        $this->join[] = [
            "type" => "LEFT",
            "sql"  => trim($join)
        ];
        return $this;
    }

    public function rightJoin(string $join): self 
    {
        $this->join[] = [
            "type" => "RIGHT",
            "sql"  => trim($join)
        ];
        return $this;
    }

    public function crossJoin(string $join): self 
    {
        $this->join[] = [
            "type" => "CROSS",
            "sql"  => trim($join)
        ];
        return $this;
    }

    public function innerJoin(string $join): self 
    {
        $this->join[] = [
            "type" => "INNER",
            "sql"  => trim($join)
        ];
        return $this;
    }

    public function rawJoin(string $join): self 
    {
        $this->join[] = [
            "type" => "",
            "sql"  => trim($join)
        ];
        return $this;
    }

    public function where($column, $operator = "=", $value = null): self
    {
        if(func_num_args() == 2)
            return $this->addWhere("AND", func_get_arg(0), "=", func_get_arg(1));

        return $this->addWhere("AND", $column, $operator, $value);
    }

    public function orWhere($column, $operator, $value = null): self
    {
        return $this->addWhere("OR", $column, $operator, $value);
    }

    public function whereNull(string $column): self 
    {
        return $this->addWhere("AND", $column, "IS NULL");
    }

    public function orWhereNull(string $column): self 
    {
        return $this->addWhere("OR", $column, "IS NULL");
    }

    public function whereNotNull(string $field): self 
    {
        return $this->addWhere("AND", $field, "IS NOT NULL");
    }

    public function orWhereNotNull(string $column): self 
    {
        return $this->addWhere("OR", $column, "IS NOT NULL");
    }

    public function whereIn(string $column, array $values): self 
    {
        return $this->addWhere("AND", $column, "IN", $values);
    }

    public function orWhereIn(string $column, array $values): self 
    {
        return $this->addWhere("OR", $column, "IN", $values);
    }

    public function whereNotIn(string $column, array $values): self 
    {
        return $this->addWhere("AND", $column, "NOT IN", $values);
    }

    public function whereBetween(string $column, array $values): self 
    {
        if(count($values) !== 2)
            throw new InvalidArgumentException("Between condition must have two values");

        return $this->addWhere("AND", $column, "BETWEEN", $values);
    }

    public function orWhereBetween(string $column, array $values): self 
    {
        if(count($values) !== 2)
            throw new InvalidArgumentException("Between condition must have two values");

        return $this->addWhere("OR", $column, "BETWEEN", $values);
    }

    public function orWhereNotBetween(string $column, array $values): self 
    {
        if (count($values) !== 2)
            throw new InvalidArgumentException("Between condition must have two values");

        return $this->addWhere("OR", $column, "NOT BETWEEN", $values);
    }

    public function whereNotBetween(string $column, array $values): self 
    {
        if(count($values) !== 2)
            throw new InvalidArgumentException("Between condition must have two values");

        return $this->addWhere("AND", $column, "NOT BETWEEN", $values);
    }

    public function rawWhere(string $where, array $values = []): self
    {
        return $this->addWhere("", $where, null, $values);
    }

    protected function addWhere($clause, $column, $operator = null, $value = null): self
    {
        if(!is_string($column) && !is_callable($column)){
            throw new \InvalidArgumentException("First parameter must be a string or callback function");
        }

        if(is_callable($column)){
            $this->startParentheses = true;  
            call_user_func($column, $this);
            $last = (count($this->where)) - 1;
            $this->where[$last]["endParentheses"] = true;
        }else{

            $operator = ($operator !== 0 && in_array($operator, $this->whereOperators)) ? $operator : "=";
            $this->where[] = [
                "clause"           => $clause,
                "column"            => trim($column),
                "operator"         => trim($operator),
                "value"            => ($value) ? $value : $operator,
                "startParentheses" => $this->startParentheses,
                "endParentheses"   => $this->endParentheses
            ];

        }

        $this->startParentheses = false;

        return $this;
    }

    public function groupBy(string $groupBy): self 
    {
        $this->groupBy = trim($groupBy);
        return $this;
    }

    public function orderBy(string $orderBy): self
    {
        $this->orderBy = trim($orderBy);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function newQuery(): QueryBuilder
    {
        return new QueryBuilder($this->pdo);
    }

    protected function execute()
    {
        $query = $this->buildStatement()["query"];
        $query .= $this->buildJoins();
        $query .= $this->buildWhere()["query"];          
        $query .= $this->buildGroupBy();
        $query .= $this->buildOrderBy();
        $query .= $this->buildLimit();
        $query .= $this->buildOffset();
        $values = array_merge($this->buildStatement()["values"], $this->buildWhere()["values"]);
        return $this->newQuery()->query($query)->values($values); 
    }
}