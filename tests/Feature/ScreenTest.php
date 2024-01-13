<?php

use Czernika\OrchidLogViewer\LogManager;
use Czernika\OrchidLogViewer\Screen\OrchidLogListScreen;
use Illuminate\Testing\TestResponse;

uses()->group('feature.screen');

afterAll(function () {
    // Rollback
    LogManager::useScreen(OrchidLogListScreen::class);
});

describe('default screen', function () {
    it('shows headings', function () {
        $this->mockLogsWith();

        /** @var TestResponse */
        $response = $this->see();

        $response
            ->assertSee('<h1 class="m-0 fw-light h3 text-black">Logs</h1>', false)
            ->assertSee('Manage app storage logs');
    })->todo();

    it('shows action buttons if user has permissions', function () {
        $this->mockLogsWith();

        /** @var TestResponse */
        $response = $this->see();

        $response
            ->assertSee(sprintf('formaction="%s"', route('platform.logs', ['method' => 'clear', 'file' => 'laravel.log'])), false)
            ->assertSee(sprintf('formaction="%s"', route('platform.logs', ['method' => 'delete', 'file' => 'laravel.log'])), false);
    })->todo();

    it('will not show clear action button if user has no permissions')->todo();

    it('will not show delete action button if user has no permissions')->todo();
});

describe('custom screen', function () {
    test('default screen can be changed')->todo();
});
