<?php


namespace Andy7917163\Einvoice;


use Illuminate\Support\ServiceProvider;

class EinvoiceServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * Register services
         *
         * @return void
         */
        $this->app->singleton('einvoice', function () {
            return new Einvoice();
        });
    }

    /**
     * Bootstrap services
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
