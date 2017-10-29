<?php
namespace Debughub\Clients\Laravel\Handlers;
use Illuminate\Contracts\Foundation\Application;
class LogHandler extends \Debughub\Clients\Php\Handlers\LogHandler
{
    private $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
        //listener for log events
        $app['events']->listen('Illuminate\Log\Events\MessageLogged', function($event) {
          $data = $event->message;
          if (isset($event->context) && count($event->context) > 0) {
              $data = [
                'Message' => $event->message,
                'Context' => $event->context
              ];
          }
          $this->addLog($data, $event->level, 'log');
        });
    }
    public function addLog($data, $name, $type = 'log')
    {
        if (is_string($data) || is_numeric($data) || is_bool($data) || is_null($data)) {
            $this->push($data, $name, $type);
        } else {
            $this->push(var_export($data, true), $name, $type);
        }
        return count($this->logs) - 1;
    }

    private function push($data, $name, $type)
    {
        $trace = debug_backtrace();
        $file = '';
        $line = '';
        if ($trace && isset($trace[8]) && isset($trace[8]['file'])) {
            $file = $trace[8]['file'];
            $line = $trace[8]['line'];
        }
        $this->logs[] = [
            'start_time' => microtime(),
            'time' => microtime(),
            'payload' => $data,
            'duration' => 0.01,
            'name' => $name,
            'type' => $type,
            'file' => $file,
            'line' => $line,
        ];
        return count($this->logs) - 1;
    }
}
