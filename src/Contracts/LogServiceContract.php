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

    /**
     * Clear selected file
     */
    public function clearFile(string $file): void;

    /**
     * Delete selected file
     */
    public function deleteFile(string $file): void;

    /**
     * Name of the level filter ley
     */
    public function levelKey(): string;

    /**
     * Name of the file level key
     */
    public function fileKey(): string;

    /**
     * Get log file name
     */
    public function logFile(string $key): string;

    /**
     * Get log files
     */
    public function logFiles(): array;
}
