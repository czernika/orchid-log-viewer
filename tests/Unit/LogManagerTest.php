<?php

use Czernika\OrchidLogViewer\Actions\ClearLogFile;
use Czernika\OrchidLogViewer\Actions\DeleteLogFile;
use Czernika\OrchidLogViewer\Layouts\OrchidLogTableLayout;
use Czernika\OrchidLogViewer\LogData;
use Czernika\OrchidLogViewer\LogManager;
use Czernika\OrchidLogViewer\Screen\OrchidLogListScreen;
use Tests\App\Actions\TestClearLogFile;
use Tests\App\Actions\TestDeleteLogFile;
use Tests\App\Layouts\TestOrchidLogTableLayout;
use Tests\App\Screen\TestOrchidLogListScreen;
use Tests\App\TestLogData;

uses()->group('unit.log-manager');

afterAll(function () {
    // Rollback
    LogManager::useScreen(OrchidLogListScreen::class);
    LogManager::useLayout(OrchidLogTableLayout::class);
    LogManager::useMapper(LogData::class);
    LogManager::clearLogFileUsing(ClearLogFile::class);
    LogManager::deleteLogFileUsing(DeleteLogFile::class);
});

describe('log manager', function () {
    it('resolves default screen', function () {
        expect(LogManager::screen())->toBe(OrchidLogListScreen::class);
    });

    it('can change screen', function () {
        LogManager::useScreen(TestOrchidLogListScreen::class);
        expect(LogManager::screen())->toBe(TestOrchidLogListScreen::class);
    });

    it('resolves default layout', function () {
        expect(LogManager::layout())->toBe(OrchidLogTableLayout::class);
    });

    it('can change layout', function () {
        LogManager::useLayout(TestOrchidLogTableLayout::class);
        expect(LogManager::layout())->toBe(TestOrchidLogTableLayout::class);
    });

    it('resolves default mapper', function () {
        expect(LogManager::mapper())->toBe(LogData::class);
    });

    it('can change mapper', function () {
        LogManager::useMapper(TestLogData::class);
        expect(LogManager::mapper())->toBe(TestLogData::class);
    });

    it('resolves default clear action', function () {
        expect(LogManager::clearLogFileAction())->toBe(ClearLogFile::class);
    });

    it('can change clear action', function () {
        LogManager::clearLogFileUsing(TestClearLogFile::class);
        expect(LogManager::clearLogFileAction())->toBe(TestClearLogFile::class);
    });

    it('resolves default delete action', function () {
        expect(LogManager::deleteLogFileAction())->toBe(DeleteLogFile::class);
    });

    it('can change delete action', function () {
        LogManager::deleteLogFileUsing(TestDeleteLogFile::class);
        expect(LogManager::deleteLogFileAction())->toBe(TestDeleteLogFile::class);
    });
});
