<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Layouts;

use Czernika\OrchidLogViewer\Filters\LogFileFilter;
use Czernika\OrchidLogViewer\Filters\LogLevelFilter;
use Orchid\Screen\Layouts\Selection;

class LogFilterLayout extends Selection
{
    public function filters(): iterable
    {
        return [
            LogFileFilter::class,
            LogLevelFilter::class,
        ];
    }
}
