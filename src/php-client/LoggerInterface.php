<?php

namespace Debughub\PhpClient;

interface LoggerInterface
{
    public function boot();
    public function registerShutdown();
}
