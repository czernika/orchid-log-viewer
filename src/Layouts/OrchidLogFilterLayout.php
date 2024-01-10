<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Layouts;

use Czernika\OrchidLogViewer\Filters\LogFilter;
use Orchid\Screen\Layouts\Selection;

class OrchidLogFilterLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            LogFilter::class,
        ];
    }
}
