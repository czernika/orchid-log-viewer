<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LogListLayout extends Table
{

    protected $target = 'logs';

    protected function columns(): iterable
    {
        return [
            TD::make('level', __('Log level'))
                ->render(function ($log) {
                    return $log['level'];
                }),

            TD::make('text', __('Log text'))
                ->render(function ($log) {

                    return $log['text'];
                }),

            TD::make('stack', __('Log stack'))
                ->render(function ($log) {
                    return $log['stack'];
                }),

            TD::make('date', __('Log date'))
                ->render(function ($log) {
                    if (1 === $log['date']) {
                        return __('No logs');
                    }

                    return $log['date'];
                }),
        ];
    }
}
