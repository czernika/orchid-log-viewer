<?php

use Czernika\OrchidLogViewer\Contracts\LogServiceContract;

uses()->group('unit.log-service');

describe('log service', function () {
    it('returns empty pagination if there are no logs', function () {
        $this->mockLogsWith([['text' => '']]);

        expect(resolve(LogServiceContract::class)->logs())->toBeEmpty();
    });

    it('returns empty pagination if there are no log files', function () {
        $this->mockLogsWith();

        expect(resolve(LogServiceContract::class)->logs())->toBeEmpty();
    });

    it('resolves correct logs if filter enabled', function () {
        $this->mockLogsWith([
            ['text' => 'ERROR log', 'level' => 'error'],
            ['text' => 'INFO log', 'level' => 'info'],
        ], 'info');

        $logs = resolve(LogServiceContract::class)->logs();

        expect($logs)
            ->first()->text()
            ->toBe('INFO log')
        ->and($logs)
            ->toHaveCount(1);
    });

    it('gets logs from correct file if file filter is present')->todo();

    it('has default log file', function () {
        expect(resolve(LogServiceContract::class)->defaultLogFile())->toBe('laravel.log');
    });

    it('has default level key name', function () {
        expect(resolve(LogServiceContract::class)->levelKey())->toBe('level');
    });

    it('has default file key name', function () {
        expect(resolve(LogServiceContract::class)->fileKey())->toBe('file');
    });

    it('has default page key name', function () {
        expect(resolve(LogServiceContract::class)->pageName())->toBe('page');
    });

    it('has default pagination options', function () {
        expect(resolve(LogServiceContract::class)->paginationOptions())->toBe(
            ['pageName' => 'page', 'path' => route('platform.logs')]
        );
    });

    it('can change default log file')->todo();

    it('can change default level key name')->todo();

    it('can change default file key name')->todo();

    it('can change default pagination options')->todo();

    it('can filter log file depends on blacklist option')->todo();
});
