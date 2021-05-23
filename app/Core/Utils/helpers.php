<?php

use App\Core\Hash;
use App\Core\Session;
use App\Core\Csrf;
use App\Core\Http\Response;
use App\Core\Utils\Str;

if (! function_exists("abort")) {
    function abort($statusCode, array $headers = []){
        $response = new Response($statusCode, $headers);
        $response->sendHeaders();
        exit;
    }
}

if (!function_exists("asset")){
    function asset($asset){
        return base_url().$asset;
    }
}

if (!function_exists("base_url")){
    function base_url(){
        return config("app.url");
    }
}

if (!function_exists("bycrypt")){
    function bycrypt($value){
        return Hash::make($value);
    }
}

if (!function_exists("env")){
    function env($key, $default = null){
        $value = getenv($key); 
        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case "true":
            case "(true)":
                return true;
            case "false":
            case "(false)":
                return false;
            case "empty":
            case "(empty)":
                return "";
            case "null":
            case "(null)":
                return;
        }

        if (strlen($value) > 1 && Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (!function_exists("class_basename")) {
    function class_basename($class) {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace("\\", "/", $class));
    }
}

if (!function_exists("config")) {
    function config($key = null, $default = null){
        $configValue = null;

        if(strpos($key, ".") !== false){
            $configValue = null;
            $config  = explode(".", $key, 2);
            $configFile = $config[0];
            $confifKey = $config[1];
            $file = "../config/".$configFile.".php";

            if(!file_exists($file))
                throw new \RuntimeException(sprintf('Config file "%s" not in config folder', $file));

            $arr = include $file;
            $config = array_slice($config, 1);
            $configValue = isset($arr[$confifKey]) ? $arr[$confifKey] : $default;
        }
       
        return  $configValue;
    }
}

if (! function_exists('csrf_field')) {
    function csrf_field() {
        echo '<input type="hidden" name="_token" value="'.csrf_token().'">';
    }
}

if(!function_exists("csrf_token")){
    function csrf_token(){
        Session::start();
        return Csrf::token();
    }
}

if (!function_exists("redirect")) {
    function redirect(string $location){
        $baseURL = base_url().$location;
        header("location: $baseURL");
        exit;
    }      
}

if (!function_exists("route")){
    function route($route, array $routeParams = []){
        
        if(count($routeParams) === 0)
            return base_url().$route;

        $parameters = "/".implode("/", $routeParams);
        return base_url().$route.$parameters;

    }
}

if (!function_exists("format_date")) {
    function format_date($date, $format = "Y-m-d"){
        $formattedDate = DateTime::createFromFormat("Y-m-d", $date);
        return $formattedDate->format($format);
    }
}

if (!function_exists("secured_encrypt")) {
    function secured_encrypt($data){
        $firstKey = base64_decode(config("app.hash_first_key"));
        $secondKey = base64_decode(config("app.hash_second_key"));  
           
        $method = config("app.cipher", "aes-256-cbc"); 
        $ivLength = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivLength);
               
        $firstEncrypted = openssl_encrypt($data, $method, $firstKey, OPENSSL_RAW_DATA, $iv);   
        $secondEncrypted = hash_hmac("sha512", $firstEncrypted, $secondKey, TRUE);
                   
        $output = base64_encode($iv.$secondEncrypted.$firstEncrypted);   
        return $output;  
    }
}

if (!function_exists("secured_decrypt")) {
    function secured_decrypt($input){
        $firstKey = base64_decode(config("app.hash_first_key"));
        $secondKey = base64_decode(config("app.hash_second_key"));     
        $mix = base64_decode($input);
               
        $method = config("app.cipher", "aes-256-cbc");
        $ivLength = openssl_cipher_iv_length($method);
                   
        $iv = substr($mix,0,$ivLength);
        $secondEncrypted = substr($mix, $ivLength,64);
        $firstEncrypted = substr($mix, $ivLength + 64);
                   
        $data = openssl_decrypt($firstEncrypted, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
        $secondEncryptedNew = hash_hmac("sha512", $firstEncrypted, $secondKey, TRUE);
           
        if (hash_equals($secondEncrypted, $secondEncryptedNew))
            return $data;

        return false;
    }
}


if(!function_exists('session')){
    function session($key = null){
        if (is_null($key)) {
            return Session::getInstance();
        }

        if (is_array($key)) {
            return Session::set($key[0], $key[1]);
        }

        return Session::get($key);
    }
}
