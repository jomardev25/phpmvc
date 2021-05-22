<?php



namespace Bootstrap;

use App\Core\DotEnv;
use App\Core\Utils\Str;

class App{

    private $url = null;
    private $controller = null;
    private $controllerPath = "../App/Controllers/";

    public function init()
    {   
        $this->loadConfig();
        $this->loadHelpers();
        $this->getUrl();

        if (empty($this->url[0])) {
            $this->defaultController();
            return false;
        }

        $this->loadController();
    }

    private function loadConfig()
    {
        (new DotEnv("../.env"))->load();
    }

    private function loadHelpers()
    {
        require_once("../app/Core/Utils/helpers.php");
    }

    private function getUrl()
    {
        $url = isset($_GET["url"]) ? $_GET["url"] : null;
        $url = rtrim($url, "/");
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode("/", $url);
        $this->url = $url;
    }

    private function defaultController()
    {
        $this->controller = new \App\Controllers\HomeController;
        $this->controller->index();
    }

    private function loadController()
    {
        $strController = ucfirst(Str::singularize($this->url[0]));
        $file = $this->controllerPath.$strController."Controller.php";
        if (file_exists($file)) {
            $controller = "\\App\Controllers\\".$strController."Controller";
            $this->controller = new $controller();
            $this->controllerMethod();
        } else {
            $this->loadErrorController();
        }
    }

    private function controllerMethod()
    {
        $length = sizeof($this->url);
        
        if($length == 1){
            $this->controller->index();
        }else{
            if (!method_exists($this->controller, $this->url[1])) {
                $this->loadErrorController();
            }else{
                $parameters = array_slice($this->url, 2);
                $method =  $this->url[1];
                call_user_func_array([$this->controller, $method], $parameters);
            }
        }
    }
    
    private function loadErrorController()
    {
        $this->controller = new \App\Controllers\ErrorController;
        $this->controller->notFound();
        exit;
    }

}