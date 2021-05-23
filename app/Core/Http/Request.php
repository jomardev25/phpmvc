<?php

namespace App\Core\Http;

class Request
{
    protected $request = [];

    public function __construct()
    {
        $this->fileInputs();
        $this->inputs();
    }

    public function getRequest()
    {
        return (object) $this->request;
    }

    public function getReferer()
    {
        return $_SERVER["HTTP_REFERER"];
    }

    public function getRequestMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    private function fileInputs()
    {
        $this->_request = $this->cleanInputs($_FILES);
    }

    private function inputs()
    {
        switch ($this->getRequestMethod()) {
            case "POST":
                $this->request = $this->cleanInputs($_POST);
                break;
            case "GET":
            case "DELETE":
                $this->request = $this->cleanInputs($_GET);
                break;
            case "PUT":
                parse_str(file_get_contents("php://input"), $this->request);
                $this->request = $this->cleanInputs($this->request);
                break;
        }
    }

    private function cleanInputs($input)
    {
        $cleanInputs = array();
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $cleanInputs[$key] = $this->cleanInputs($value);
            }
        } else {
            if (get_magic_quotes_gpc()) {
                $input = trim(stripslashes($input));
            }
            $input = strip_tags($input);
            $cleanInputs = trim($input);
        }
        return $cleanInputs;
    }

    public function get($key)
    {
        return isset($this->request[$key]) ? $this->request[$key] : null;
    }

    public function all()
    {
        return $this->request;
    }
}