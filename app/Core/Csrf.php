<?php

namespace App\Core;

class Csrf
{
    const TOKEN_KEY = "XSRF-TOKEN";

    private static function randomToken()
    {
        $key = config("app.key");
        $userAgent = (isset($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : null;
        $clientIp = self::getRealIpAddr();
        $hashedToken = base64_encode(password_hash(base64_encode($key.$userAgent.$clientIp), PASSWORD_BCRYPT));
        self::setToken($hashedToken);
        return $hashedToken;
    }

    private static function getRealIpAddr()
    {
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else{
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        return $ip;
    }

    private static function setToken($token)
    {
        Session::start();

        $tokenList = unserialize(Session::get(static::TOKEN_KEY));

        if (!is_array($tokenList))
            $tokenList = array();

        array_push($tokenList, $token);
        Session::set(static::TOKEN_KEY, serialize($tokenList));
    }

    private static function checkToken($token)
    {
        Session::start();
        $tokenList = Session::get(static::TOKEN_KEY);
        if(is_array($tokenList) && in_array($token, $tokenList)){
            self::removeToken($token);
            return true;
        }else{
            return false;
        }
    }

    private static function removeToken($token)
    {
        Session::start();
        $tokenList = unserialize(Session::get(static::TOKEN_KEY));
        $index = array_search($token, $tokenList);
        unset($tokenList[$index]);
        Session::set(static::TOKEN_KEY, serialize($tokenList));
    }

    public static function token()
    {
        return self::randomToken();
    }

    public static function flushToken()
    {
        Session::start();
        Session::set(static::TOKEN_KEY, null);
    }
}