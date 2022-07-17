<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer;

use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Czernika\OrchidLogViewer\Services\LogService;
use Illuminate\Support\ServiceProvider;

class OrchidLogViewerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__).'/config/orchid-log.php' => config_path('orchid-log.php'),
        ], 'olv-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/config/orchid-log.php', 'orchid-log');

        $this->app->bind(LogServiceContract::class, LogService::class);
    }
}
