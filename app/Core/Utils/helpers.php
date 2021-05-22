<?php
use App\Core\Utils\Str;

if (!function_exists("env")) {

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
            //$config = array_slice($config, 1);

            $configValue = \App\Core\Utils\Arr::get($arr, "default.connections.mysql.driver");
            print_r($configValue);
            //$configValue = isset($arr[$confifKey]) ? $arr[$confifKey] : $default;
        }
       
        return  $configValue;
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
