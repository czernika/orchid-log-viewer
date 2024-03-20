<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Orchid\Support\Testing\ScreenTesting;

class TestFeatureCase extends TestCase
{
    use RefreshDatabase, ScreenTesting;

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
