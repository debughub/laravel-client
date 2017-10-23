<?php
namespace Debughub\PhpClient;

class Logger implements LoggerInterface
{
    public $queryHandler;
    public $exceptionHandler;
    public $logHandler;
    public $requestHandler;
    public $responseHandler;
    public $startTime;
    public $endTime;
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->startTime = microtime();
    }

    public function boot()
    {
        if ($this->config->getEnabled()) {
            $this->logHandler = new Handlers\LogHandler();
            $this->queryHandler = new Handlers\QueryHandler($this->config);
            $this->exceptionHandler = new Handlers\ExceptionHandler();
            $this->requestHandler = new Handlers\RequestHandler($this->config);
            $this->responseHandler = new Handlers\ResponseHandler($this->config);
            $this->registerShutdown();

        }

    }


    public function registerShutdown()
    {
      register_shutdown_function(function(){
        if (is_array($this->config->getIgnoreUrls()) && in_array($this->requestHandler->url, $this->config->getIgnoreUrls())) {
            return false;
        }
        $payload = $this->createPayload();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config->getEndpoint());
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000);
        curl_exec ($ch);
        curl_close ($ch);
        return true;
      });
    }

    private function createPayload()
    {

        $endTime = microtime();
        $timeStartFloat = microtimeFloat($this->startTime);
        $timeEndFloat = microtimeFloat($endTime);
        $duration = $timeEndFloat - $_SERVER['REQUEST_TIME_FLOAT'];
        return [
          'data' =>[
              'boot_time' => $this->startTime,
              'start_time' => $_SERVER['REQUEST_TIME_FLOAT'],
              'end_time' => $endTime,
              'queries' => $this->queryHandler->getData(),
              'exceptions' => $this->exceptionHandler->getData(),
              'logs' => $this->logHandler->getData(),
              'request' => $this->requestHandler->getData(),
              'response' => $this->responseHandler->getData(),
              'duration' => $duration,
          ],
          'api_key' => $this->config->getApiKey(),
          'project_key' => $this->config->getProjectKey(),
        ];
    }

        public function route($route = '') {
            if ($this->config->getEnabled()) {
                $this->requestHandler->route = $route;
            }
        }
        public function response($response = '') {
            if ($this->config->getEnabled()) {
                $this->responseHandler->response = $route;
            }
        }
        public function query($query = '', $data = '', $duration = '', $connection = '') {
            if ($this->config->getEnabled()) {
                $this->queryHandler->addQuery([
                    'query' => $query,
                    'data' => $data,
                    'duration' => $duration,
                    'connection' => $connection,
                ]);
            }
        }


        public function startQuery($query = '', $data = '', $duration = '', $connection = '') {
            if ($this->config->getEnabled()) {
                return $this->queryHandler->addQuery([
                    'query' => $query,
                    'data' => $data,
                    'duration' => $duration,
                    'connection' => $connection,
                ]);
            }
            return 0;
        }
        public function endQuery($index = false) {
            if ($this->config->getEnabled()) {
                $this->queryHandler->endQuery($index);
            }
        }

        public function log($data = '', $name = 'info'){
            if ($this->config->getEnabled()) {
                $this->logHandler->addLog($data, $name);
            }
        }

        public function startLog($data = '', $name = 'info') {
            if ($this->config->getEnabled()) {
                return $this->logHandler->addLog($data, $name);
            }
            return 0;
        }
        public function endLog($index = false) {
            if ($this->config->getEnabled()) {
                return $this->logHandler->endLog($index);

            }
        }
}
if (!function_exists('microtimeFloat')) {
    function microtimeFloat($time)
    {
        list($usec, $sec) = explode(" ", $time);
        return ((float)$usec + (float)$sec);
    }
}
