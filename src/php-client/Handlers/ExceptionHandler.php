<?php

namespace Debughub\PhpClient\Handlers;

use Debughub\PhpClient\Reportable;

class ExceptionHandler implements Reportable
{
    public $exceptions = [];

    public function getData()
    {
      return $this->exceptions;
    }
}
