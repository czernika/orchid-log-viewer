<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Traits;

use Czernika\OrchidLogViewer\Layouts\LogListLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;

trait HasLogViewerScreen
{
    /**
     * Get log screen command bar
     *
     * @return Button[]
     */
    public function logViewerCommandBar(): array
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
    public function logViewerLayout(): array
    {
        return [
            LogListLayout::class,
        ];
    }

    /**
     * Clear selected log file
     *
     * @return void
     */
    public function logViewerClearFile(): void
    {
        Toast::success(__('Selected fle was cleared'));
    }

    /**
     * Delete selected log file
     *
     * @return void
     */
    public function logViewerDeleteFile(): void
    {
        Toast::success(__('Selected fle was deleted'));
    }
}
