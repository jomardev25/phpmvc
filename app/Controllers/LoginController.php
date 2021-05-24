<?php
namespace App\Controllers;

use App\Models\User;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Controller;
class LoginController extends Controller
{
    protected $header = "";
    protected $footer = "";
    private $routesMethod = [
        "index" => "GET",
        "post" => "POST"
    ];

    public function __construct()
    {   
        parent::__construct();
        $this->routeMethods($this->routesMethod);
    }
    
    public function index()
    {
        $this->view("login.index");
    }

    public function post()
    {
       // $user = User::login($this->request->get("username"), $this->request->get("password"));
        //Auth::login($user);
        //print_r($_SESSION);
        $this->view("login.index");
    }

}