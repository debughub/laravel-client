<?php
namespace Debughub\Clients\Laravel\Handlers;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Debughub\Clients\Php\Config;
class ResponseHandler extends \Debughub\Clients\Php\Handlers\ResponseHandler
{
    private $app;
    public function __construct(Config $config, Application $app)
    {
        parent::__construct($config);
        $this->app = $app;
//        listener for view events
        $app['events']->listen('composing:*', function ($view = null, $data = null) {
            if (is_a($view, \Illuminate\View\View::class)){
                $this->parseView($view);
            } else {
                $this->parseView($data);
            }
        });
        $this->response = '';
        $app['events']->listen('Illuminate\Foundation\Http\Events\RequestHandled', function ($event = null) {
          if ($event){
            $request = $event;
            $maxLength = 1000;
            if (strlen($request->response->getContent()) > $maxLength) {
              $data = substr($request->response->getContent(), 0, $maxLength);
            } else {
              $data = $request->response->getContent();
            }
            $this->response = $data;
          }
        });
    }
    private function parseView($data)
    {
        if (is_a($data, \Illuminate\View\View::class)) {
            $this->views[] = [
                'name' => $data->getName(),
                'path' => $data->getPath()
            ];
        }
        if (is_array($data) && isset($data[0]) && is_a($data, \Illuminate\View\View::class)){
          $this->views[] = [
            'name' => $data[0]->getName(),
            'path' => $data[0]->getPath()
          ];
        }
    }
}
