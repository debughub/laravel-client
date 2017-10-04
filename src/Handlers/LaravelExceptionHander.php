<?php
namespace Debughub\LaravelClient\Handlers;
use Illuminate\Contracts\Foundation\Application;
class LaravelExceptionHandler extends \Debughub\PhpClient\Handlers\ExceptionHandler
{
    private $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
        //listener for error events
        $app['events']->listen('*', function($event = null, $data = null) {
            if (is_string($event) && $event == 'error') {
                $this->exceptions[] = $this->parseError($data);
            }
        });
    }
    public function parseError($data) {
      $errorTrace = [];
      $traces = [];
      if (method_exists($data, 'getTrace')) {
        $traces = $data->getTrace();
      }
      foreach ($data->getTrace() as $trace) {
        $errorTrace[] = [
          'file' => isset($trace['file']) ? $trace['file'] : '',
          'line' => isset($trace['line']) ? $trace['line'] : '',
          'function' => isset($trace['function']) ? $trace['function'] : '',
          'class' => isset($trace['class']) ? $trace['class'] : '',
          'type' => isset($trace['type']) ? $trace['type'] : '',
        ];
      }
      $message = '';
      if (method_exists($data, 'getMessage')) {
        $message = $data->getMessage();
      }
      $file = '';
      if (method_exists($data, 'getFile')) {
        $file = $data->getFile();
      }
      $code = '';
      if (method_exists($data, 'getCode')) {
        $code = $data->getCode();
      }
      $line = '';
      if (method_exists($data, 'getLine')) {
        $line = $data->getLine();
      }
      $severity = '';
      if (method_exists($data, 'getSeverity')) {
        $severity = $data->getSeverity();
      }
      return [
        'message' => $message,
        'file' => $file,
        'code' => $code,
        'line' => $line,
        'severity' => $severity,
        'trace' => $errorTrace,
        'end_time' => microtime()
      ];
    }
}
