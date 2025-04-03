<?php
class Request {
    private $params = [];
    private $method;
    private $path;
    private $data = [];

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
       
        $input = file_get_contents('php://input');
        if ($input) {
            $this->data = json_decode($input, true) ?? [];
        }
        
       
        $this->data = array_merge($this->data, $_REQUEST);
        
      
        $this->data = $this->sanitize($this->data);
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPath() {
        return $this->path;
    }

    public function getParams() {
        return $this->params;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function input($key, $default = null) {
        return $this->data[$key] ?? $default;
    }

    public function all() {
        return $this->data;
    }

    private function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}