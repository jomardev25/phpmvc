<?php

return [

    "default" => env("DB_CONNECTION", "mysql"),

    "connections" => [
        "mysql" => [
            "driver" => "mysql",
            "host" => env("DB_HOST", "127.0.0.1"),
            "port" => env("DB_PORT", "3309"),
            "database" => env("DB_DATABASE", "hrms"),
            "username" => env("DB_USERNAME", "root"),
            "password" => env("DB_PASSWORD", "limsehleng"),
            "charset" => "utf8",
            "options" => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]
        ]
    ]
];