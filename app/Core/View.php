<?php

namespace App\Core;

class View
{
    private $viewDirPath = "../resources/views/";

    public function render($view, array $data = [])
    {   
        extract($data);
        require $this->viewDirPath.$this->getViewPath($view).".php";
    }

    private function getViewPath(string $view)
    {
        if(strpos($view, ".") !== false){
            $view = explode(".", $view);
            $view = implode("/", $view);
            return $view;
        }

        return $view;
    }
}