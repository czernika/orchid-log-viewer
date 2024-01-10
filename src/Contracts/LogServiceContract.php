<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface LogServiceContract
{
    /**
     * Get paginated list of logs
     */
    public function logs(): LengthAwarePaginator;

    /**
     * Resolve selected file (from request)
     */
    public function resolveSelectedFile(): string;

    public function clearFile(string $file): void;

    public function deleteFile(string $file): void;
}
