<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Traits;

use Czernika\OrchidLogViewer\Layouts\LogFilterLayout;
use Czernika\OrchidLogViewer\Layouts\LogListLayout;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

trait HasLogViewerScreen
{

    /**
     * Get log screen command bar
     *
     * @return Button[]
     */
    protected function logViewerCommandBar(): array
    {
        return [
            Button::make(__('Clear file'))
                ->type(Color::WARNING())
                ->icon('disc')
                ->method('logViewerClearFile'),

            Button::make(__('Delete file'))
                ->type(Color::DANGER())
                ->icon('trash')
                ->method('logViewerDeleteFile'),
        ];
    }

    /**
     * Get log layout
     *
     * @return array
     */
    protected function logViewerLayout(): array
    {
        return [
            Layout::modal('showLogStackModal', [
                Layout::rows([
                    TextArea::make('stack')
                        ->rows(32),
                ]),
            ])
                ->title(__('Log stack trace'))
                ->withoutApplyButton()
                ->async('asyncGetLogStack')
                ->size(Modal::SIZE_LG),

            LogFilterLayout::class,
            LogListLayout::class,
        ];
    }

    /**
     * Get log stack message
     *
     * @param string|null $stack
     * @return array
     */
    protected function asyncGetLogStack(?string $stack = null): array
    {
        return compact('stack');
    }

    /**
     * Clear selected log file
     *
     * @return void
     */
    protected function logViewerClearFile(?string $file = null): void
    {
        $file = $this->setFile($file);

        Storage::disk(config('orchid-log.disk', 'log'))->put($file, '');

        Toast::success(__('File :file was cleared', compact('file')));
    }

    /**
     * Delete selected log file
     *
     * @return void
     */
    protected function logViewerDeleteFile(?string $file = null): void
    {
        $file = $this->setFile($file);

        Storage::disk(config('orchid-log.disk', 'log'))->delete($file);

        Toast::success(__('File :file was deleted', compact('file')));
    }

    /**
     * Get first file if not selected
     *
     * @param string|null $file
     * @return string
     */
    private function setFile(?string $file = null): string
    {
        if (! $file) {
            $file = head($this->logViewer->getFiles(true));
        }

        return $file;
    }
}
