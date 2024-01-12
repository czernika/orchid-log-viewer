<?php

use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Mockery\MockInterface;

uses()->group('unit.log-service');

describe('log service', function () {
    it('returns empty pagination if there are no logs', function () {
        
    })->todo();

    it('returns empty pagination if there are no log files', function () {
        $this->partialMock(LogServiceContract::class, function (MockInterface $mock) {
            $mock->shouldReceive('rawLogs')->once()->andReturn([]);
        });

        /** @var LogServiceContract $service */
        $service = resolve(LogServiceContract::class);

        expect($service->logs())->toBeEmpty();
    })->todo();

    it('filters logs if level filter is present')->todo();

    it('gets logs from correct file if file filter is present')->todo();

    it('has default log file')->todo();

    it('has default level key name')->todo();

    it('has default file key name')->todo();

    it('has default pagination options')->todo();

    it('can change default log file')->todo();

    it('can change default level key name')->todo();

    it('can change default file key name')->todo();

    it('can change default pagination options')->todo();

    it('can filter log file depends on blacklist option')->todo();
});