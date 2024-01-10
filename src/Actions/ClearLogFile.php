<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Actions;

use Czernika\OrchidLogViewer\Contracts\HandlesLogs;
use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Orchid\Support\Facades\Toast;

class ClearLogFile implements HandlesLogs
{
    public function handle(LogServiceContract $logService, string $file): void
    {
        try {
            $logService->clearFile($file);

            Toast::success(trans('orchid-log::messages.actions.clear.success_message', compact('file')));
        } catch (\Throwable $th) {
            Toast::error($th->getMessage());
        }
    }
}
