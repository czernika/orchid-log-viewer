<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Contracts;

interface HandlesLogs
{
    public function handle(LogServiceContract $logService, string $file): void;
}
