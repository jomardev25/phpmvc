<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        Auth::handleLogin();
    }

    public function index()
    {
        return $this->view("home.index");
    }
}