<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Layouts;

use Orchid\Screen\Layouts\Table;

class OrchidLogTableLayout extends Table
{
    protected $target = 'logs';

    protected function columns(): iterable
    {
        return [];
    }
}
