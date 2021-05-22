<?php

namespace App\Core;

use BadMethodCallException;
use App\Core\View;
use App\Core\Http\Response;
use App\Core\Contracts\ResponseInterface;

abstract class Controller implements ResponseInterface
{
    protected $view;
    protected $hasHeader = true;
    protected $hasFooter = true;
    protected $header = "layout.header";
    protected $footer = "layout.footer";

    public function __construct()
    {
        $this->view = new View();
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