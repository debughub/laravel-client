<?php

namespace Debughub\PhpClient\Handlers;

use Debughub\PhpClient\Reportable;

class LogHandler implements Reportable
{

    private $logs = [];

    public function __construct()
    {

    }

    public function addLog($data, $name, $type = 'log')
    {
        if (is_string($data) || is_numeric($data) || is_bool($data) || is_null($data)) {
            $this->push($data, $name, $type);
        } else {
            $this->push(var_export($data, true), $name, $type);
        }
    }

    private function push($data, $name, $type)
    {
        $trace = debug_backtrace();
        $file = '';
        $line = '';
        if ($trace && isset($trace[3]) && isset($trace[3]['file'])) {
            $file = $trace[3]['file'];
            $line = $trace[3]['line'];
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
    public function endlog($index = null) {
        // if index is provided, get the item with key of the index. If not, get the last query
        if ($index === null) {
            $index = count($this->logs) - 1;
        }
        if (isset($this->logs[$index])) {
            $this->logs[$index]['time'] = microtime();
            $this->logs[$index]['duration'] = \Debughub\PhpClient\microtimeFloat($this->logs[$index]['time']) - \Debughub\PhpClient\microtimeFloat($this->logs[$index]['start_time']);
        }
    }

    public function getData()
    {
        foreach ($this->logs as $key => $value) {
            unset($this->logs[$key]['start_time']);
        }
        return $this->logs;
    }
}
