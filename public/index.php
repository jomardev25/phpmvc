<?php

function __autoload($class){
    $class = ltrim($class, "\\");
    $filaname = "";
    $namespace = "";
    if ($lastNsPos = strripos($class, "\\")) {
        $namespace = substr($class, 0, $lastNsPos);
        $class = substr($class, $lastNsPos + 1);

        $filaname  = str_replace("\\", DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $filaname .= str_replace("_", DIRECTORY_SEPARATOR, $class) . ".php";
    require "../".$filaname;
}

$app = new \Bootstrap\App;
$app->init();

print_r(config("database.default.connections"));