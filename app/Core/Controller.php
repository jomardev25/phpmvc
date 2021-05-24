<?php

namespace App\Core;

use BadMethodCallException;
use App\Core\View;
use App\Core\Session;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Contracts\Http\ControllerInterface;
abstract class Controller implements ControllerInterface
{
    protected $request;
    protected $view;
    protected $hasHeader = true;
    protected $hasFooter = true;
    protected $header = "layout.header";
    protected $footer = "layout.footer";
    protected $routeMethods = [];

    public function __construct()
    {
        Session::start();
        $this->view = new View();
        $this->request = new Request();
    }

    public function request()
    {
        return $this->request->getRequest();
    }

    public function method(string $routeMethod)
    {
        if($this->request->getRequestMethod() !== strtoupper($routeMethod)){
            abort(\App\Core\Http\Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function view(string $view, array $data = [], int $status = Response::HTTP_OK, array $headers = [])
    { 
        $this->getResponseHeader($status, $headers);
        $this->getHeader();
        $this->view->render($view, $data);
        $this->getFooter();
        return $this;
    }

    public function json(array $data = [], int $status = Response::HTTP_OK, array $headers = [])
    {
        $this->getResponseHeader($status, $headers);
        return $this;
    }

    public function download($file, $name = null, array $headers = [], string $disposition = "attachment")
    {
        return $this;
    }

    public function excludeHeader()
    {
        $this->hasHeader = false;
        return $this;
    }

    public function excludeFooter()
    {
        $this->hasFooter = false;
        return $this;
    }

    public function routeMethods(array $routeMethods = [])
    {
        if(count($routeMethods) === 0)
            return;

        $segments = $this->request->segments();
        $method = $this->request->getRequestMethod();
        if(count($segments) === 1){
            $requestedRoute = "index";
        }else{
            $requestedRoute = $segments[1];
        }
        
        if(!isset($routeMethods[$requestedRoute]))
            return;

        if( $method !== strtoupper($routeMethods[$requestedRoute])){
            abort(\App\Core\Http\Response::HTTP_METHOD_NOT_ALLOWED);
        }
        
    }


    private function getHeader()
    {
        if($this->hasHeader && $this->header !== ""){
            $this->view->render($this->header); 
        }
    }

    private function getFooter()
    {
       if($this->hasFooter && $this->footer !== ""){
           $this->view->render($this->footer); 
        }
    }

    private function getResponseHeader(int $status = Response::HTTP_OK, array $headers = [])
    {
        (new Response($status, $headers))->sendHeaders();
    }

    public function __call($method, $parameters)
    {
        throw new BadMethodCallException("Method [{$method}] does not exist on [".get_class($this).'].');
    }
}