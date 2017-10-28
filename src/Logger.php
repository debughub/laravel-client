<?php
namespace Debughub\Clients\Laravel;
use DB;
use App;
use \Debughub\Clients\Php\Config;

class Logger extends \Debughub\Clients\Php\Logger
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
            $this->logHandler = new \Debughub\Clients\Php\Handlers\LogHandler();
            $this->queryHandler = new Handlers\QueryHandler($this->app);
            $this->exceptionHandler = new Handlers\ExceptionHandler($this->app);
            $this->requestHandler = new Handlers\RequestHandler($this->config, $this->app);
            $this->responseHandler = new Handlers\ResponseHandler($this->config, $this->app);
            $this->registerShutdown();
        }
    }
}
