<?php

use Czernika\OrchidLogViewer\LogData;
use Illuminate\Testing\TestResponse;

uses()->group('feature.log-data');

describe('log data', function () {
    it('shows correct log data on a screen if there are some logs', function () {
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

    it('will not show stack trace button if there is no log stack trace', function () {
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

describe('custom log data', function () {
    it('resolves custom columns with no error')->todo();

    it('resolves custom camel case shortcut columns with no error')->todo();

    it('resolves custom snake case shortcut columns with no error')->todo();
});
