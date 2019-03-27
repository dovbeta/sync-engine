<?php
namespace ESG\SyncEngine;

use ESG\SyncEngine\Console\Commands\ExportCategories;
use ESG\SyncEngine\Console\Commands\ExportPages;
use Illuminate\Support\ServiceProvider as Laravel_ServiceProvider;

class ServiceProvider extends Laravel_ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ExportCategories::class,
                ExportPages::class,
            ]);
        }
        $this->publishes([
            __DIR__.'/config/sync.php' => config_path('sync.php'),
        ]);
    }

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/config/sync.php', 'sync');
    }

}