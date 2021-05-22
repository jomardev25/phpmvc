<?php

use App\Core\Http\Router;

Router::get("/users", "UserController@index");