<?php

namespace Tests;

use Czernika\OrchidLogViewer\Services\LogService;
use Mockery\MockInterface;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Dashboard;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Watson\Active\Active;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Alert' => Alert::class,
            'Active' => Active::class,
            'Breadcrumbs' => Breadcrumbs::class,
            'Dashboard' => Dashboard::class,
        ];
    }

    public function mockLogsWith(array $logs = [], ?string $levelFilter = null): void
    {
        $this->partialMock(LogService::class, function (MockInterface $mock) use ($logs, $levelFilter) {
            $mock->shouldReceive('setSelectedFile');

            $mock->shouldReceive('levelFilterEnabled')->andReturn(! is_null($levelFilter));
            $mock->shouldReceive('levelFilterValue')->andReturn($levelFilter);

            $mock->shouldReceive('rawLogs')->andReturn($logs);
        });
    }
}
