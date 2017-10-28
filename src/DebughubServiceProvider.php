<?php
namespace Debughub\Clients\Laravel;
use Illuminate\Support\ServiceProvider;
use Debughub\Clients\Php\Config;

class DebughubServiceProvider extends ServiceProvider
{
    private $config;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->config->getEnabled()) {
            $logger = new Logger($this->config, $this->app);
            $logger->boot();
            $this->app->singleton('debughub', function () use($logger) {
                return $logger;
            });
        }
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
    }
    private function configure()
    {

        $config = realpath(__DIR__.'/../config/debughub.php');
        $this->mergeConfigFrom($config, 'debughub');
        $this->config = new Config();
        $this->config->setApiKey($this->app->config->get('debughub.api_key'));
        $this->config->setProjectKey($this->app->config->get('debughub.project_key'));
        $this->config->setEndpoint($this->app->config->get('debughub.endpoint'));
        $this->config->setGitRoot($this->app->config->get('debughub.git_root'));
        $this->config->setBlacklistParams($this->app->config->get('debughub.blacklist_params'));
        $this->config->setEnabled($this->app->config->get('debughub.enabled') ? true : false);
        $this->config->setSendQueryData($this->app->config->get('debughub.send_query_data') ? true : false);
        $this->config->setIgnoreUrls($this->app->config->get('debughub.ignore_urls') ? $this->app->config->get('debughub.ignore_urls') : ['123']);
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['debughub'];
    }


}
