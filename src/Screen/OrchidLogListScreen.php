<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Screen;

use Czernika\OrchidLogViewer\Actions\ClearLogFile;
use Czernika\OrchidLogViewer\Actions\DeleteLogFile;
use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Czernika\OrchidLogViewer\Layouts\OrchidLogFilterLayout;
use Czernika\OrchidLogViewer\LogManager;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class OrchidLogListScreen extends Screen
{
    public function __construct(
        protected readonly LogServiceContract $logService,
    ) {
    }

    public function query(): iterable
    {
        return [
            'logs' => $this->logService->logs(),
        ];
    }

    public function name(): ?string
    {
        return trans('orchid-log::messages.screen.name');
    }

    public function description(): ?string
    {
        return trans('orchid-log::messages.screen.description');
    }

    public function permission(): ?iterable
    {
        return config('orchid-log.screen.permissions');
    }

    public function commandBar()
    {
        $file = $this->logService->resolveSelectedFile();

        return [
            Button::make(trans('orchid-log::messages.actions.clear.btn_label', compact('file')))
                ->method('clear', compact('file'))
                ->type(Color::WARNING)
                ->icon('bs.disc')
                ->canSee($this->canSeeCleanBtn())
                ->confirm(trans('orchid-log::messages.actions.clear.confirm_message', compact('file'))),

            Button::make(trans('orchid-log::messages.actions.delete.btn_label', compact('file')))
                ->method('delete', compact('file'))
                ->type(Color::DANGER)
                ->icon('bs.trash')
                ->canSee($this->canSeeDeleteBtn())
                ->confirm(trans('orchid-log::messages.actions.delete.confirm_message', compact('file'))),
        ];
    }

    public function layout(): iterable
    {
        $layouts = [
            Layout::modal('logModal', [
                Layout::rows([
                    TextArea::make('stack')
                        ->readonly(false) // TODO make an option
                        ->rows(40),
                ]),
            ])
                ->async('asyncGetLog')
                ->withoutApplyButton()
                ->title(trans('orchid-log::messages.layout.stack'))
                ->size(Modal::SIZE_LG),

            LogManager::layout(),
        ];

        if (config('orchid-log.filters.enabled', true)) {
            $layouts = [OrchidLogFilterLayout::class, ...$layouts];
        }

        return $layouts;
    }

    public function asyncGetLog(string $stack): array
    {
        return compact('stack');
    }

    protected function canSeeCleanBtn(): bool
    {
        return true; // TODO permissions
    }

    protected function canSeeDeleteBtn(): bool
    {
        return true; // TODO permissions
    }

    public function clear(string $file)
    {
        /** @var ClearLogFile $clearAction */
        $clearAction = app(LogManager::clearLogFileAction());

        $clearAction->handle($this->logService, $file);

        return back();
    }

    public function delete(string $file)
    {
        /** @var DeleteLogFile $deleteAction */
        $deleteAction = app(LogManager::deleteLogFileAction());

        $deleteAction->handle($this->logService, $file);

        // Need to clear any query params, therefore cannot use `back()`
        return to_route(config('orchid-log.screen.route', 'platform.logs'));
    }
}
