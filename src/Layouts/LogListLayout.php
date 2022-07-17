<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Layouts;

use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class LogListLayout extends Table
{

    protected $target = 'logs';

    protected function columns(): iterable
    {
        return [
            TD::make('level', __('Log level'))
                ->render(function ($log) {
                    return '<span class="text-'.$log['level_class'].'">'.$log['level'].'</span>';
                }),

            TD::make('text', __('Log text'))
                ->render(function ($log) {

                    return '<span style="white-space: normal;">'.$log['text'].'</span>';
                }),

            TD::make('stack', __('Log stack'))
                ->render(function ($log) {
                    if ('' === $log['stack']) {
                        return __('No trace');
                    }

                    return ModalToggle::make(__('View'))
                        ->modalTitle(__('Log stack trace'))
                        ->modal('showLogStackModal')
                        ->asyncParameters([
                            'stack' => $log['stack'],
                        ]);
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
