<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer;

use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Czernika\OrchidLogViewer\Services\LogService;
use Illuminate\Routing\Router;
use Orchid\Platform\Dashboard;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
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
            $name = config('orchid-log.screen.route', 'platform.logs');
            
            $route
                ->screen('logs', LogManager::screen())
                ->name($name)
                ->breadcrumbs(fn (Trail $trail) => $trail
                    ->parent('platform.index')
                    ->push(trans('orchid-log::messages.screen.name'), route($name)));
        }
    }

    public function menu(): array
    {
        if (config('orchid-log.menu.register', true)) {
            return [
                Menu::make(trans('orchid-log::messages.menu.name'))
                    ->icon('bs.bug')
                    ->canSee(true) // TODO permissions
                    ->title(trans('orchid-log::messages.menu.title'))
                    ->route('platform.logs'),
            ];
        }

        return [];
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
