<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer;

use Czernika\OrchidLogViewer\Screen\OrchidLogListScreen;
use Illuminate\Routing\Router;
use Orchid\Platform\Dashboard;
use Orchid\Platform\OrchidServiceProvider;
use Tabuna\Breadcrumbs\Trail;

class OrchidLogServiceProvider extends OrchidServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom($this->getConfigFile(), 'orchid-log');
    }

    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        $this->publishes([
            $this->getConfigFile() => config_path('orchid-log.php'),
        ]);
    }

    public function routes(Router $route): void
    {
        if (config('orchid-log.screen.discover', true)) {
            $route
                ->screen('logs', OrchidLogListScreen::class)
                ->name('platform.logs')
                ->breadcrumbs(fn (Trail $trail) => $trail
                    ->parent('platform.index')
                    ->push(__('Logs'), route('platform.logs')));
        }
    }

    protected function getConfigFile(): string
    {
        return dirname(__DIR__, 1).'/config/orchid-log.php';
    }
}
