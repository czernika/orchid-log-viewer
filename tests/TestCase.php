<?php

namespace Tests;

use App\Models\User;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Orchid\Support\Testing\ScreenTesting;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Dashboard;
use Tabuna\Breadcrumbs\Breadcrumbs;
use Watson\Active\Active;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench, FastRefreshDatabase, ScreenTesting;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
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

    public function admin()
    {
        return User::factory()->admin()->create();
    }
}
