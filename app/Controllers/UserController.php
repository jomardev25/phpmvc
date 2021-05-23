<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Core\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::handleLogin();
    }
    
    public function index()
    {
        //$user = new User();
        //$user = $user->query();
        //$result = $user->rawWhere("where id=:id", ["id" => 1])->get();
        //echo "<pre>"; print_r(\App\Core\Hash::make("31313131"));
        $this->view("users.index");
    }

    public function show($year, $month, $type)
    {
    
    }
}