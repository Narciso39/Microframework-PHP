<?php
class Response
{
    private $statusCode = 200;
    private $headers = [];
    private $body;

    public function status($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function header($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function json($data)
    {
        $this->header('Content-Type', 'application/json');
        $this->body = json_encode($data);
        $this->send();
    }

    public function send()
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->body;
    }
}
