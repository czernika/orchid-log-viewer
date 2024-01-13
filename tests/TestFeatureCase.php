<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Testing\TestResponse;
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
}
