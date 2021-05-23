<?php

namespace App\Core;
class Session
{
    private static $sessionStarted = false;
    private static $instance = null;

    public static function start()
    {

        @session_start();

        if(is_null(static::get("XSRF-TOKEN")))
            static::set("XSRF-TOKEN", null);
    }
    
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
        return $_SESSION[$key];
    }
    
    public static function get($key)
    {
        $value = null;
        if (isset($_SESSION[$key]))
            $value = $_SESSION[$key];
        
        return $value;
    }
    
    public static function destroy()
    {
        session_destroy();
    }

    public static function getInstance() 
    {
        if (is_null(self::$instance)){ 
            self::$instance = new self(); 
        }
        
        return self::$instance;
    }
}