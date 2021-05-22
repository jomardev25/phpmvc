<?php

namespace App\Core\Http;

use App\Core\Utils\Arr;

class Router
{
    /* private $request;
    private $action;
    private $method;
    private $route;
    private $properties = [];
    private $allowedMethods = ["GET", "POST", "PUT", "DELETE", "PATCH"];

    public function __construct()
    {

    }

    public function getActionMethod()
    {
        return Arr::last(explode('@', $this->getActionName()));
    }

    public function getActionName()
    {
        return is_string($this->action) ? $this->action : "Closure";
    } */

   /*  public function __calls ($name, $args)
    {   
        list($route, $action) = $args;
        $this->action = $action;
        $this->method = $name;
        $this->route = $route;
        if(!in_array(strtoupper($name), $this->allowedMethods)){
            $this->methodNotAllowed();
        }

        //echo $this->getActionMethod();
    } */

    public function __call($name, $args)
    {
        list($route, $action) = $args;
        print_r($name);
    }
    
    public static function __callStatic($name, $args)
    {
        list($route, $action) = $args;
        print_r($name);
    }

    /* public function methodNotAllowed()
    {
        echo "<h1>Method not Allowed.</h1>";
        //header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    public function __get($key)
    {
        if(isset($this->properties[$key])) {
            return $this->properties[$key];
        }
    }

    public function __set($key, $value)
    {
        $this->properties[$key] = $value;
    } */

}