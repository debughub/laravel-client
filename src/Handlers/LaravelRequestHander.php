<?php
namespace Debughub\LaravelClient\Handlers;
use Illuminate\Contracts\Foundation\Application;
use Debughub\PhpClient\Config;
class LaravelRequestHandler extends \Debughub\PhpClient\Handlers\RequestHandler
{
    public function __construct(Config $config, Application $app)
    {
        parent::__construct($config);
        //set up laravel request variables
        if (strpos($app['request']->server('SCRIPT_NAME'), 'artisan') !== false && $app['request']->server('SCRIPT_NAME') != '/index.php') {
            $this->method = 'artisan';
            $this->url = 'php ' . implode(' ', $app['request']->server('argv'));

        } elseif (strpos($app['request']->server('SCRIPT_NAME'), 'phpunit') !== false && $app['request']->server('SCRIPT_NAME') != '/index.php') {
            $this->method = 'phpunit';
            $this->url = implode(' ', $app['request']->server('argv'));

        } else {
            $this->method = strtolower($app['request']->server('REQUEST_METHOD'));
            $this->url = $app['request']->url();
        }
        $app['events']->listen('Illuminate\Routing\Events\RouteMatched', function($event) {
          if (method_exists('Illuminate\Routing\Route', 'getUri')) {
            $this->route = $event->route->getUri();
          } else {
            $this->route = $event->route->uri;
          }
        });
    }
}
