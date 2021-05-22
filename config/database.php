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
            "collation" => "utf8_unicode_ci",
            "prefix" => "",
            "strict" => true,
            "engine" => null,
        ],

        "pgsql" => [
            "driver" => "pgsql",
            "host" => env("DB_HOST", "127.0.0.1"),
            "port" => env("DB_PORT", "5432"),
            "database" => env("DB_DATABASE", "forge"),
            "username" => env("DB_USERNAME", "forge"),
            "password" => env("DB_PASSWORD", ""),
            "charset" => "utf8",
            "prefix" => "",
            "schema" => "public",
            "sslmode" => "prefer",
        ],

        "sqlsrv" => [
            "driver" => "sqlsrv",
            "host" => env("DB_HOST", "localhost"),
            "port" => env("DB_PORT", "1433"),
            "database" => env("DB_DATABASE", "forge"),
            "username" => env("DB_USERNAME", "forge"),
            "password" => env("DB_PASSWORD", ""),
            "charset" => "utf8",
            "prefix" => "",
        ],
    ]

];