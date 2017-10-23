<?php

namespace Debughub\PhpClient;



class Debughub
{
    protected $config;
    protected $configPath;
    protected $logger;

    public function __construct($configPath = '', $logger = false)
    {
        $this->configPath = $configPath;
        $this->configure();
        if (!$logger) {
            $this->logger = new Logger($this->config);
        } elseif(is_a($logger, LoggerInterface::class)) {
            $this->logger = $logger;
        }
        $this->logger->boot();
    }

    private function configure()
    {
        // load the default config
        $defaultConfigPath = realpath(__DIR__.'/../../config/debughub.php');
        $config = require($defaultConfigPath);

        // load the custom config
        $customConfig = [];
        if (file_exists($this->configPath)) {
            $customConfig = require($this->configPath);
        }
        // merge both configs - the custom one should override the default one
        foreach ($customConfig as $key => $value) {
            $config[$key] = $value;
        }

        // create config object
        $this->config = new Config();
        $this->config->setApiKey($config['api_key']);
        $this->config->setProjectKey($config['project_key']);
        $this->config->setEndpoint($config['endpoint']);
        $this->config->setGitRoot($config['git_root']);
        $this->config->setBlacklistParams($config['blacklist_params']);
        $this->config->setEnabled($config['enabled'] ? true : false);
        $this->config->setSendQueryData($config['send_query_data'] ? true : false);
        $this->config->setIgnoreUrls($config['ignore_urls'] ? $config['ignore_urls'] : []);

    }

    // Proxy methods

    public function route($route = '') {
        $this->logger->route($route);
    }
    public function response($response = '') {
        $this->logger->response($route);
    }
    public function query($query = '', $data = '', $duration = '', $connection = '') {
        $this->logger->query($query, $data, $duration, $connection);
    }

    public function startQuery($query = '', $data = '', $duration = '', $connection = '') {
        $this->logger->startQuery($query, $data, $duration, $connection);

    }
    public function endQuery($index = false) {
        $this->logger->endQuery($index);
    }

    public function log($data = '', $name = 'info'){
        $this->logger->log($data, $name);
    }

    public function startLog($data = '', $name = 'info') {
        $this->logger->startLog($data, $name);

    }
    public function endLog($index = false) {
        $this->logger->endLog($index);

    }
}
