<?php

namespace App\Core;
use App\Core\Utils\Str;
use App\Core\Database\Datatabase;
abstract class Model
{
    protected $pdo;
    protected $connection = "default";
    protected $table;
    protected $primaryKey = "id";
    protected $hidden = [];
    protected $query;
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";

    public function __construct()
    {
        $database = new Datatabase($this->getDefaultConnection());
        $this->pdo = $database->connection($this->connection);
        $this->query = $this->pdo->table($this->getTable());
    }

    public static function query()
    {
        $self = new static;
        $self->query = $self->pdo->table($self->getTable());
        return $self->query;
    }

    public static function find($id, array $attributes = ["*"])
    {
        return (new static)->query()->where((new static)->getKeyName(), $id)->first($attributes);
    }

    public static function all(array $attributes = ["*"])
    {
        return (new static)->query->all($attributes);
    }

    public function get(array $attributes = ["*"])
    {
        return $this->query->get($attributes);
    }

    public static function create(array $values)
    {
        return (new static)->query()->insert($values)->exec();
    }

    public function insertGetId(array $values)
    {
        return $this->query->insert($values)->lastId();
    }

    public function update(array $values)
    {
        //return $this->query->update($values);
    }

    public function where($column, $operator = "=", $value = null)
    {
        if(func_num_args() == 2)
            $this->query = $this->query->where($column, "=", $value);
        else
            $this->query =$this->query->where($column, $operator, $value);

        return $this->query;
    }

    public function pluck($column, $key = null)
    {
        
    }

    public function getTable()
    {
        if (! isset($this->table)) {
            return str_replace(
                '\\', '', Str::snake(Str::pluralize(class_basename($this)))
            );
        }

        return $this->table;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function getConnectionName()
    {
        return $this->connection;
    }

    public function setConnection($name)
    {
        $this->connection = $name;
        return $this;
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    public function setHidden(array $hidden)
    {
        $this->hidden = $hidden;
    } 

    public function setKeyName($key)
    {
        $this->primaryKey = $key;
        return $this;
    }

    private function getDefaultConnection()
    {
        $config = include "../config/database.php";
        $connection = null;
        if(isset($config[$this->connection]))
            $connection = $config["connections"][$config[$this->connection]];

        return $connection;
    }

}