<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{   
    protected $hidden = [
        "password", "remember_token",
    ];

    public static function login(string $username, string $password)
    {
        return (new static)->query()->where("email", $username)->where("password", $password)->limit(1)->first(["name", "email"]);
    }
}
