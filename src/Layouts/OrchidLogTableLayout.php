<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Layouts;

use Czernika\OrchidLogViewer\Contracts\LogDataContract;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Color;

class OrchidLogTableLayout extends Table
{
    protected $target = 'logs';

    protected function levelColumn(): TD
    {
        return TD::make('level', trans('orchid-log::messages.layout.stack'))
            ->render(function (LogDataContract $log) {
                return Blade::render(
                    /** translators: 1 - Bootstrap icon name; 2 - log level; 3 - Bootstrap text-color class */
                    sprintf('<x-orchid-icon path="bs.%1$s" class="me-1 %3$s" /> %2$s',
                        $log->levelImg(),
                        Str::ucfirst($log->level()),
                        $log->levelColorClass(),
                    )
                );
            });
    }

    protected function textColumn(): TD
    {
        return TD::make('text', trans('orchid-log::messages.layout.text'))
            ->cantHide()
            ->width('70%');
    }

    protected function stackTraceColumn(): TD
    {
        return TD::make('stack', trans('orchid-log::messages.layout.stack'))
            ->render(function (LogDataContract $log) {
                if ('' === $log->stack()) {
                    return '';
                }

                return ModalToggle::make(trans('orchid-log::messages.layout.stack'))
                    ->modal('logModal')
                    ->asyncParameters([
                        'stack' => $log->stack(),
                    ])
                    ->icon('bs.arrows-fullscreen')
                    ->type(Color::WARNING);
            });
    }

    protected function dateColumn(): TD
    {
        return TD::make('date', trans('orchid-log::messages.layout.date'))
            ->alignRight()
            ->cantHide()
            ->usingComponent(DateTimeSplit::class);
    }

    protected function columns(): iterable
    {
        return [
            $this->levelColumn(),
            $this->textColumn(),
            $this->stackTraceColumn(),
            $this->dateColumn(),
        ];
    }
}
