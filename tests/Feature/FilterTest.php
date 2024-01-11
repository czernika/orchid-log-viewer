<?php

use Czernika\OrchidLogViewer\LogData;
use Illuminate\Testing\TestResponse;

uses()->group('feature.filter');

describe('level filter', function () {
    it('filters logs by level', function () {
        $this->mockLogsWith([
            new LogData([
                'text' => 'Log ERROR message',
                'level' => 'error',
            ]),
            new LogData([
                'text' => 'Log INFO message',
                'level' => 'info',
            ]),
        ]);

        /** @var TestResponse */
        $response = $this->seeWithParameters([
            'level' => 'info',
        ]);

        $response->assertDontSee('Log ERROR message');
    })->todo();
});
