<?php
namespace App\Controllers;

use App\Core\Controller;

class UserController extends Controller
{
    public function index()
    {
       $this->view("users.index", []);
    }

    public function show($year, $month, $type)
    {
        print_r (func_get_args());

    }
}