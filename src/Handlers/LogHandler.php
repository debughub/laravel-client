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
}
