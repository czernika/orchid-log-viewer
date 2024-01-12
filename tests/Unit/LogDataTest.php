<?php

use Czernika\OrchidLogViewer\LogData;

uses()->group('unit.log-data');

describe('log data', function () {
    it('gets correct data from raw log', function (string $method, string $expected) {
        $mapper = new LogData([
            'context' => 'Context',
            'level' => 'error',
            'folder' => '/some/folder',
            'level_class' => 'error-class',
            'level_img' => 'exclamation-mark',
            'text' => 'Error text',
            'stack' => 'Stack trace',
            'in_file' => 'laravel.php',
            'date' => '2024-01-09 09:46:32',
        ]);

        expect($mapper->$method())->toBe($expected);
    })->with([
        'context data' => ['context', 'Context'],
        'level data' => ['level', 'error'],
        'folder data' => ['folder', '/some/folder'],
        'level class data' => ['levelClass', 'error-class'],
        'level img data' => ['levelImg', 'exclamation-mark'],
        'text data' => ['text', 'Error text'],
        'stack data' => ['stack', 'Stack trace'],
        'in file data' => ['inFile', 'laravel.php'],
        'date data' => ['date', '2024-01-09 09:46:32'],
    ]);

    it('resolves correct level color class according to log level', function (string $level, $expectedClass) {
        $mapper = new LogData(compact('level'));

        expect($mapper->levelColorClass())->toBe($expectedClass);
    })->with([
        ['debug', 'text-primary'],
        ['info', 'text-primary'],
        ['notice', 'text-primary'],
        ['processed', 'text-primary'],
        ['warning', 'text-warning'],
        ['failed', 'text-warning'],
        ['error', 'text-danger'],
        ['critical', 'text-danger'],
        ['alert', 'text-danger'],
        ['emergency', 'text-danger'],
    ]);
});
