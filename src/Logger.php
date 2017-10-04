<?php
namespace Debughub\LaravelClient;
use DB;
use App;
use \Debughub\PhpClient\Config;
class Logger extends \Debughub\PhpClient\Logger
{
    private $app;
    public function __construct(Config $config, $app)
    {
        $this->config = $config;
        $this->app = $app;
        $this->startTime = microtime();
    }

    public function boot()
    {
        if ($this->config->getEnabled()) {
            $this->logHandler = new \Debughub\PhpClient\Handlers\LogHandler();
            $this->queryHandler = new Handlers\LaravelQueryHandler($this->app);
            $this->exceptionHandler = new Handlers\LaravelExceptionHandler($this->app);
            $this->requestHandler = new Handlers\LaravelRequestHandler($this->config, $this->app);
            $this->responseHandler = new Handlers\LaravelResponseHandler($this->config, $this->app);
            $this->registerShutdown();
        }
    }
}
