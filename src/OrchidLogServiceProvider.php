<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer;

use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Czernika\OrchidLogViewer\Services\LogService;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;
use Orchid\Platform\Dashboard;
use Orchid\Platform\OrchidServiceProvider;
use Tabuna\Breadcrumbs\Trail;

class OrchidLogServiceProvider extends OrchidServiceProvider
{
    public function register()
    {
        $this->app->bind(LogServiceContract::class, LogService::class);

        $this->mergeConfigFrom($this->getConfigFilePath(), 'orchid-log');
    }

    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        $this->loadTranslationsFrom($this->getLangFilesPath(), 'orchid-log');

        $this->publishes([
            $this->getLangFilesPath() => $this->app->langPath('vendor/orchid-log'),
            $this->getConfigFilePath() => config_path('orchid-log.php'),
        ]);
    }

    public function routes(Router $route): void
    {
        if (config('orchid-log.screen.discover', true)) {
            $route
                ->screen('logs', LogManager::screen())
                ->name(config('orchid-log.screen.route', 'platform.logs'))
                ->breadcrumbs(fn (Trail $trail) => $trail
                    ->parent('platform.index')
                    ->push(trans('orchid-log::messages.screen.name'), route('platform.logs')));
        }
    }

    protected function getConfigFilePath(): string
    {
        return $this->getFilesPath('config/orchid-log.php');
    }

    protected function getLangFilesPath(): string
    {
        return $this->getFilesPath('lang');
    }

    private function getFilesPath(string $path): string
    {
        return dirname(__DIR__, 1).DIRECTORY_SEPARATOR.$path;
    }
}
