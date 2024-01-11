<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer;

use Czernika\OrchidLogViewer\Actions\ClearLogFile;
use Czernika\OrchidLogViewer\Actions\DeleteLogFile;
use Czernika\OrchidLogViewer\Contracts\HandlesLogs;
use Czernika\OrchidLogViewer\Layouts\OrchidLogTableLayout;
use Czernika\OrchidLogViewer\Screen\OrchidLogListScreen;

class LogManager
{
    protected static string $screen = OrchidLogListScreen::class;

    protected static string $layout = OrchidLogTableLayout::class;

    protected static string $mapper = LogData::class;

    protected static string $clearAction = ClearLogFile::class;

    protected static string $deleteAction = DeleteLogFile::class;

    public static function screen(): string
    {
        return static::$screen;
    }

    public static function useScreen(string $screen): void
    {
        static::$screen = $screen;
    }

    public static function layout(): string
    {
        return static::$layout;
    }

    public static function useLayout(string $layout): void
    {
        static::$layout = $layout;
    }

    public static function mapper(): string
    {
        return static::$mapper;
    }

    public static function useMapper(string $mapper): void
    {
        static::$mapper = $mapper;
    }

    public static function clearLogFileAction(): string
    {
        return static::$clearAction;
    }

    public function clearLogFile(): HandlesLogs
    {
        return app(static::clearLogFileAction());
    }

    public static function clearLogFileUsing(string $action): void
    {
        static::$clearAction = $action;
    }

    public static function deleteLogFileAction(): string
    {
        return static::$deleteAction;
    }

    public function deleteLogFile(): HandlesLogs
    {
        return app(static::deleteLogFileAction());
    }

    public static function deleteLogFileUsing(string $action): void
    {
        static::$deleteAction = $action;
    }
}
