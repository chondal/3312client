<?php

namespace Chondal\TicketSoporte;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Chondal\TicketSoporte\View\Components\FormularioSoporte;


class Client3312ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', '3312client');
        $this->mergeConfigFrom(__DIR__.'/config/3312client.php', '3312client');

        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/3312client'),
            __DIR__.'/config/3312client.php' => config_path('3312client.php'),
        ]);
    }

    public function register()
    {
        $this->app->singleton(\Chondal\TicketSoporte\Services\TicketService::class, function ($app) {
            $config = config('3312client');
    
            return new \Chondal\TicketSoporte\Services\TicketService(
                $config['url'],
                $config['identificador_unico'],
                $config['token']
            );
        });
        Blade::component('formulario-soporte', FormularioSoporte::class);
    }
}
