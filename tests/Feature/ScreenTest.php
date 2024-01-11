<?php

use Czernika\OrchidLogViewer\LogData;
use Czernika\OrchidLogViewer\LogManager;
use Czernika\OrchidLogViewer\Screen\OrchidLogListScreen;
use Illuminate\Testing\TestResponse;

uses()->group('feature.screen');

beforeEach(function () {
    $this->withoutExceptionHandling();
});

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
    });

    it('action buttons', function () {
        $this->mockLogsWith();

        /** @var TestResponse */
        $response = $this->see();

        $response
            ->assertSee(sprintf('formaction="%s"', route('platform.logs', ['method' => 'clear', 'file' => 'laravel.log'])), false)
            ->assertSee(sprintf('formaction="%s"', route('platform.logs', ['method' => 'delete', 'file' => 'laravel.log'])), false);
    });

    it('shows empty table if there are no logs', function () {
        $this->mockLogsWith();

        /** @var TestResponse */
        $response = $this->see();

        $response->assertSee('There are no objects currently displayed');
    });

    it('shows correct log data if there are some logs', function () {
        $this->mockLogsWith([
            new LogData([
                'text' => 'Log message',
                'stack' => 'Some really long stack trace',
                'date' => '2024-01-01 12:00:00',
                'level' => 'error',
                'level_img' => 'exclamation-triangle',
            ]),
        ]);

        /** @var TestResponse */
        $response = $this->see();

        $response
            ->assertSee('Log message') // log message
            ->assertSee('Error') // log level with capitalized first letter
            ->assertSee('path="bs.exclamation-triangle"', false) // log level icon
            ->assertSee('<time class="mb-0 text-capitalize">Jan 1, 2024<span class="text-muted d-block">Mon, 12:00</span></time>', false) // formatted date
            ->assertSee('data-controller="modal-toggle"', false) // stack trace button
            ->assertSee('data-modal-toggle-params=\'{"stack":"Some really long stack trace"}\'', false); // stack trace param
    });

    it('will not show stack trace button if there are no log stack trace', function () {
        $this->mockLogsWith([
            new LogData([
                'text' => 'Log message',
                'stack' => '', // No stack trace
            ]),
        ]);

        /** @var TestResponse */
        $response = $this->see();

        $response->assertDontSee('data-controller="modal-toggle"', false);
    });
});
