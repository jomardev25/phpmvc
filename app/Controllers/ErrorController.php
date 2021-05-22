<?php
namespace App\Controllers;

use App\Core\Controller;

class ErrorController extends Controller {
    
    protected $header = "";
    protected $footer = "";
    
    public function notFound()
    {
        $this->view("errors.404");
    }

}