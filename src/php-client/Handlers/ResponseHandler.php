<?php

namespace Debughub\PhpClient\Handlers;

use Debughub\PhpClient\Reportable;
use Debughub\PhpClient\Config;


class ResponseHandler implements Reportable
{
    public $response;
    public $views;
    public $headers;
    public $gitBranchName;
    public $response_code;
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }



    public function getData()
    {
        $this->getGitBranchName();

        return [
            'response' => $this->response,
            'views' => [],
            'headers' => headers_list(),
            'git_branch_name' => $this->gitBranchName,
            'response_code' => http_response_code() ? http_response_code() : '-',
            'memory_usage' => memory_get_peak_usage(),
        ];
    }


    protected function getGitBranchName()
    {
        $shellOutput = [];
        $this->gitBranchName = null;
        if (!empty($this->config->getGitRoot())) {
            exec('cd "'.$this->config->getGitRoot().'" &&  git branch | ' . "grep ' * '", $shellOutput);
            foreach ($shellOutput as $line) {
                if (strpos($line, '* ') !== false) {
                    $this->gitBranchName = trim(strtolower(str_replace('* ', '', $line)));
                }
            }
        }
    }


}
