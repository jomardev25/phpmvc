<?php
return [

    "name" => env("APP_NAME", "CMS"),

    "key" => env("APP_KEY"),
    
    "timezone" => env("TIMEZONE", "Asia/Manila"),

    "date_format" => env("DATE_FORMAT", "m/d/Y"),

    "datetime_format" => env("DATETIME_FORMAT", "m/d/Y H:i:s"),

    "hash_first_key" => env("APP_HASH_FIRST_KEY"),

    "hash_second_key" => env("APP_HASH_SECOND_KEY"),
    
    "url" => env("APP_URL", "http://localhost/phpmvc/public/"),
    
];