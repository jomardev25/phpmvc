<?php

namespace App\Core;
use App\Core\Session;

class Auth
{
    public function __construct()
    {
        Session::start(); 
    }

    public static function user()
    {
        return Session::get("auth");
    }

    public static function login($user)
    {
        return Session::set("auth", serialize($user));
    }

    public static function logout()
    {
        Session::destroy();
        redirect("../login");
    }

    public static function handleLogin()
    {
        $isLoggedIn = Session::get("auth");
        if ($isLoggedIn == false) {
            Session::destroy();
            redirect("login");
        }
    }
}