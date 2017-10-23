<?php

namespace Debughub\PhpClient\Handlers;

use Debughub\PhpClient\Reportable;
use Debughub\PhpClient\Config;

class QueryHandler implements Reportable
{
    public $queries = [];
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function addQuery($data) {
        $queryData = [];
        if ($this->config->getSendQueryData()) {
            $queryData = $data['data'] ? $data['data'] : [];
        }
        $data = [
          'query' => $data['query'] ? $data['query'] : '',
          'data' => $queryData,
          'duration' => $data['duration'] ? round($data['duration'] * 1000, 2): 0,
          'start_time' => microtime(),
          'end_time' => microtime(),
          'connection' => $data['connection'] ? $data['connection'] : '',
        ];
        $this->queries[] = $data;
        return count($this->queries) - 1;
    }

    public function endQuery($index = false) {
        // if index is provided, get the item with key of the index. If not, get the last query
        if ($index === false) {
            $index = count($this->queries) - 1;
        }

        if (isset($this->queries[$index])) {
            $this->queries[$index]['end_time'] = microtime();
            $this->queries[$index]['duration'] = round((\Debughub\PhpClient\microtimeFloat($this->queries[$index]['end_time']) - \Debughub\PhpClient\microtimeFloat($this->queries[$index]['start_time'])) * 1000, 2);
        }
    }

    public function getData()
    {
        foreach ($this->queries as $key => $value) {
            unset($this->queries[$key]['start_time']);
        }
        return $this->queries;
    }
}
