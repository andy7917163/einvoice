<?php


namespace Andy7917163\Einvoice\Tests;

use Andy7917163\Einvoice\EinvoiceServiceProvider;
use Andy7917163\Einvoice\Facades\Einvoice;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            EinvoiceServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'einvoice' => Einvoice::class,
        ];
    }
}