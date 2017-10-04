<?php
namespace Debughub\LaravelClient\Facades;
use Illuminate\Support\Facades\Facade;
class Debughub extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'debughub'; }
}
