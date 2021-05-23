<?php

namespace App\Core\Traits\Database;

use App\Core\Utils\Arr;

trait Query
{
    protected function buildStatement(): array
    {
        $query = "";
        $values = [];

        // select
        if($this->select){
            $attributes = $this->buildColumns($this->select);
            $query = "SELECT $attributes FROM `".$this->table."`";
        }

        // insert
        if(!empty($this->insert)){
            $values = $this->insert;
            $queryKeys = implode(", ", array_keys($values));
            $queryValues = ":".implode(", :", array_keys($values));
            $query = "INSERT INTO `".$this->table."` (".$queryKeys.") VALUES (".$queryValues.")";
        }

        // update
        if (!empty($this->update)){
            $set      = $this->update;
            $querySet = NULL;
            foreach($set as $key => $value){
                $querySet .= "$key = :$key,";
                $values[$key] = $value;
            }
            $querySet = rtrim($querySet, ",");
            $query = "UPDATE `".$this->table."` SET ".$querySet;
        }
        // delete
        if($this->delete){
            $query = "DELETE FROM `".$this->table."`";
        }

        return [
            "query" => $query,
            "values" => $values
        ];
    }

    protected function buildJoins(): string
    {
        $query = "";
        if (!empty($this->join)) {
            foreach ($this->join as $join) {
                if ($join["type"] === "") {
                    $query .= " ".$join["sql"];
                } else {
                    $query .= " ".$join["type"]." JOIN ".$join["sql"];
                }
            }
        }
        return $query;
    }

    protected function buildWhere(): array
    {
        $query = "";
        $values = [];

        if(!empty($this->where)){
            foreach($this->where as $i => $where){

                if(in_array($where["operator"], ["IS NULL", "IS NOT NULL"])){

                    $whereRaw = "`".$where["column"]."` ".$where["operator"];

                }elseif(in_array($where["operator"], ["IN", "NOT IN"])){

                    $in = "";
                    $column = str_replace(".", "", $where["column"]);
                    foreach ($where["value"] as $x => $item) {
                        $key = $column."in".$x;
                        $in .= ":$key, ";
                        $values[$key] = $item;
                    }
                    $in = rtrim($in, ", ");
                    $whereRaw = "`".$where["column"]."` ".$where["operator"]." (".$in.")";
                
                }elseif(in_array($where["operator"], ["BETWEEN", "NOT BETWEEN"])){
                    
                    $column = str_replace(".", "", $where["column"]);
                    $whereRaw = "`".$where["column"]."` ".$where["operator"]." :".$column."btw0 AND :".$column."btw1";
                    $values[$column."btw0"] = $where["value"][0];
                    $values[$column."btw1"] = $where["value"][1];

                }elseif ($where["clause"] === ""){

                    $whereRaw = $where["column"];
                    if(is_array($where["value"]))
                        $values = array_merge($values, $where["value"]);  

                }else{
                   
                    $column          = str_replace(".", "", $where["column"]).$i;
                    $whereRaw        = "`".$where["column"]."` ".$where["operator"]." :".$column;
                    $values[$column] = $where["value"];
                }

                if($where["clause"] !== ""){
                    if($i == 0){
                        $query .= " WHERE ".$whereRaw;
                    }else{
                        $query .= " ".$where["clause"]." ";
                        $query .= $where["startParentheses"] ? "(": "";
                        $query .= $whereRaw;
                        $query .= $where["endParentheses"] ? ")": "";
                    }
                }else{
                    $query .= " ".$whereRaw." ";
                }
            }  
        }

        return [
            "query" => $query,
            "values" => $values
        ];
    }

    protected function buildGroupBy(): string
    {
        $query = "";
        if ($this->groupBy)
            $query = " GROUP BY `".$this->groupBy."`";

        return $query;
    }

    protected function buildOrderBy(): string
    {
        $query = "";
        if($this->orderBy){
            $query = " ORDER BY `".$this->orderBy."`";
        }

        return $query;
    }

    protected function buildLimit(): string
    {
        $query = "";
        if ($this->limit)
            $query = " LIMIT ".$this->limit;

        return $query;
    }

    protected function buildOffset(): string
    {
        $query = "";
        if($this->offset)
            $query = " OFFSET ".$this->offset;

        return $query;
    }

    protected function buildColumns($attributes)
    {
        if(is_array($attributes)){
            if(count($attributes) == 1 && $attributes[0] == "*"){
                $colums = "*";
                //$colums = array_diff($colums, $this->getHidden());
            }else{
                $table = "`".$this->table."`.";
                $colums = $table."`".implode("`, ".$table."`", $attributes) . "`";
            }
        }else{
            $colums = $attributes;
        }

        return $colums;
    }

    protected function getColumnList()
    {
        $table = $this->table;
        $database = $this->getPDOConnection()->query("select database()")->fetchColumn();
        $params = [
            "table_schema" => $database,
            "table_name" => $table
        ];

        $query = "SELECT column_name AS `column_name`
                  FROM information_schema.columns
                  WHERE table_schema = :table_schema and table_name = :table_name";

        $result = $this->newQuery()->query($query)->setFetchMode(\PDO::FETCH_ASSOC)->values($params)->get();

        return Arr::flatten($result);
    }
}