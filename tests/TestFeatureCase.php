<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Testing\TestResponse;
use Mockery\MockInterface;
use Orchid\Support\Testing\ScreenTesting;
use Plannr\Laravel\FastRefreshDatabase\Traits\FastRefreshDatabase;

class TestFeatureCase extends TestCase
{
    use FastRefreshDatabase, ScreenTesting;

    public function see(): TestResponse
    {
        return $this->screen('platform.logs')
            ->actingAs(User::factory()->admin()->create())
            ->display();
    }

    public function seeWithParameters(array $parameters): TestResponse
    {
        return $this->screen('platform.logs')
            ->parameters($parameters)
            ->actingAs(User::factory()->admin()->create())
            ->display();
    }

    public function mockLogsWith(array $logs = [], string $file = 'laravel.log'): void
    {
        $this->partialMock(LogServiceContract::class, function (MockInterface $mock) use ($logs, $file) {
            $mock->shouldReceive('logs')->once()->andReturn(
                new LengthAwarePaginator($logs, count($logs), 15),
            );

            $mock->shouldReceive('resolveSelectedFile')->once()->andReturn($file);

            $mock->shouldReceive('levelKey');
            $mock->shouldReceive('fileKey');
            $mock->shouldReceive('logFiles');
        });
    }
}
