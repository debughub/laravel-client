<?php

namespace Debughub\PhpClient\Handlers;

use Debughub\PhpClient\Reportable;
use Debughub\PhpClient\Config;


class RequestHandler implements Reportable
{
    public $params;
    public $headers;
    public $method;
    public $route;
    public $url;
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    private function filterParams()
    {
        $this->getParams();
        if (is_array($this->params)) {
            foreach ($this->params as $name => $param) {
                if (in_array($name, $this->config->getBlacklistParams())) {
                    $this->params[$name] = 'blacklisted param';
                }
            }
        }
    }

    private function getParams()
    {
        $this->params = array_merge($_GET, $_POST);
    }

    public function getData()
    {
        $this->filterParams();
        if (!$this->method) {
            $this->method = strtolower(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'Console');
        }
        if (!$this->url) {
            $this->url = strtolower(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . strtolower(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
        }
        return [
            'params' => $this->params,
            'headers' => getallheaders(),
            'method' => $this->method,
            'route' => $this->route,
            'url' => $this->url,
        ];
    }
}
if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
           $headers = [];
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}
