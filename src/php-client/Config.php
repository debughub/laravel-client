<?php

namespace Debughub\PhpClient;


class Config
{
    private $apiKey;
    private $projectKey;
    private $endpoint;
    private $gitRoot;
    private $blacklistParams;
    private $enabled;
    private $sendQueryData;
    private $ignoreUrls;

    public function setApiKey($key)
    {
        $this->apiKey = $key;
    }
    public function setProjectKey($key)
    {
        $this->projectKey = $key;
    }
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }
    public function setGitRoot($gitRoot)
    {
        $this->gitRoot = $gitRoot;
    }
    public function setBlacklistParams($params)
    {
        $this->blacklistParams = $params;
    }
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
    public function setSendQueryData($sendQueryData)
    {
        $this->sendQueryData = $sendQueryData;
    }

    public function setIgnoreUrls($ignoreUrls)
    {
        $this->ignoreUrls = $ignoreUrls;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }
    public function getProjectKey()
    {
        return $this->projectKey;
    }
    public function getEndpoint()
    {
        return $this->endpoint;
    }
    public function getGitRoot()
    {
        return $this->gitRoot;
    }
    public function getBlacklistParams()
    {
        return $this->blacklistParams;
    }
    public function getEnabled()
    {
        return $this->enabled;
    }
    public function getSendQueryData()
    {
        return $this->sendQueryData;
    }
    public function getIgnoreUrls()
    {
        return $this->ignoreUrls;
    }
}
