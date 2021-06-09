<?php


namespace Andy7917163\Einvoice\Facades;


use Illuminate\Support\Facades\Facade;

class Einvoice extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'einvoice';
    }
}
