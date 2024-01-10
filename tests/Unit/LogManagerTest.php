<?php

use Czernika\OrchidLogViewer\Actions\ClearLogFile;
use Czernika\OrchidLogViewer\Actions\DeleteLogFile;
use Czernika\OrchidLogViewer\Layouts\OrchidLogTableLayout;
use Czernika\OrchidLogViewer\LogData;
use Czernika\OrchidLogViewer\LogManager;
use Czernika\OrchidLogViewer\Screen\OrchidLogListScreen;
use Tests\Actions\TestClearLogFile;
use Tests\Actions\TestDeleteLogFile;
use Tests\Data\TestLogData;
use Tests\Layouts\TestOrchidLogTableLayout;
use Tests\Screen\TestOrchidLogListScreen;

describe('log manager', function () {
    it('resolves default screen', function () {
        expect(LogManager::screen())->toBe(OrchidLogListScreen::class);
    });

    it('can change screen', function () {
        LogManager::useScreen(TestOrchidLogListScreen::class);
        expect(LogManager::screen())->toBe(TestOrchidLogListScreen::class);

        // Rollback
        LogManager::useScreen(OrchidLogListScreen::class);
    });

    it('resolves default layout', function () {
        expect(LogManager::layout())->toBe(OrchidLogTableLayout::class);
    });

    it('can change layout', function () {
        LogManager::useLayout(TestOrchidLogTableLayout::class);
        expect(LogManager::layout())->toBe(TestOrchidLogTableLayout::class);

        // Rollback
        LogManager::useLayout(OrchidLogTableLayout::class);
    });

    it('resolves default mapper', function () {
        expect(LogManager::mapper())->toBe(LogData::class);
    });

    it('can change mapper', function () {
        LogManager::useMapper(TestLogData::class);
        expect(LogManager::mapper())->toBe(TestLogData::class);

        // Rollback
        LogManager::useMapper(LogData::class);
    });

    it('resolves default clear action', function () {
        expect(LogManager::clearLogFileAction())->toBe(ClearLogFile::class);
    });

    it('can change clear action', function () {
        LogManager::clearLogFileUsing(TestClearLogFile::class);
        expect(LogManager::clearLogFileAction())->toBe(TestClearLogFile::class);

        // Rollback
        LogManager::clearLogFileUsing(ClearLogFile::class);
    });

    it('resolves default delete action', function () {
        expect(LogManager::deleteLogFileAction())->toBe(DeleteLogFile::class);
    });

    it('can change delete action', function () {
        LogManager::deleteLogFileUsing(TestDeleteLogFile::class);
        expect(LogManager::deleteLogFileAction())->toBe(TestDeleteLogFile::class);

        // Rollback
        LogManager::deleteLogFileUsing(DeleteLogFile::class);
    });
});
