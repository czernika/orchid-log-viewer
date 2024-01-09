<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Screen;

use Czernika\OrchidLogViewer\Layouts\OrchidLogTableLayout;
use Orchid\Screen\Screen;

class OrchidLogListScreen extends Screen
{
    public function query()
    {
        $logs = [];

        return [
            'logs' => $logs,
        ];
    }

    public function name(): ?string
    {
        return __('Logs');
    }

    public function layout(): iterable
    {
        return [
            OrchidLogTableLayout::class,
        ];
    }
}
