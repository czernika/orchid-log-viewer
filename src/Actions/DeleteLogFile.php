<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Actions;

use Czernika\OrchidLogViewer\Contracts\HandlesLogs;
use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Orchid\Support\Facades\Toast;

class DeleteLogFile implements HandlesLogs
{
    public function handle(LogServiceContract $logService, string $file): void
    {
        try {
            $logService->deleteFile($file);

            Toast::success(trans('orchid-log::messages.actions.delete.success_message', compact('file')));
        } catch (\Throwable $th) {
            Toast::error($th->getMessage());
        }
    }
}
