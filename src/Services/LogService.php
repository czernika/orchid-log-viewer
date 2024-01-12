<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Services;

use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Czernika\OrchidLogViewer\LogData;
use Czernika\OrchidLogViewer\LogManager;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class LogService implements LogServiceContract
{
    public function __construct(
        protected readonly LaravelLogViewer $logViewer,
        protected readonly Request $request,
    ) {
    }

    /**
     * Get paginated logs
     */
    public function logs(): LengthAwarePaginator
    {
        $this->setSelectedFile();

        $logs = $this->rawLogs();

        // Return early if there are no logs or when there are no log files at all.
        // Package creates at least one line anyway with empty text
        // but there are no errors can be without text
        if ($this->logFileDoesntExist($logs) || $this->logsAreEmpty($logs)) {
            return new LengthAwarePaginator([], 0, $this->perPage());
        }

        $logs = collect($logs)

            // Filter logs by level
            ->when($this->request->query->has($this->levelKey()), function (Collection $collection) {
                return $collection->filter(fn (array $log) => $log['level'] === $this->request->query->get($this->levelKey()));
            })

            // Convert into LogData object
            ->mapInto(LogManager::mapper());

        return new LengthAwarePaginator(
            $logs->forPage($page = Paginator::resolveCurrentPage(), $this->perPage()),
            $logs->count(),
            $this->perPage(),
            $page,
            ['pageName' => $this->pageName(), 'path' => route(config('orchid-log.screen.route', 'platform.logs'))],
        );
    }

    /**
     * Amount of logs to show
     */
    protected function perPage(): int
    {
        return config('orchid-log.table.per_page', 15);
    }

    /**
     * Pagination `pageName` option
     */
    protected function pageName(): string
    {
        return config('orchid-log.table.page_name', 'page');
    }

    /**
     * Get an array of log data
     */
    public function rawLogs(): array
    {
        return $this->logViewer->all();
    }

    /**
     * The situation when there are lo logs at all is only one -
     * there are no any log files. To prevent error we check this data
     */
    protected function logFileDoesntExist(array $logs): bool
    {
        return empty($logs);
    }

    /**
     * If there are at least on log file it will return at least one log
     * which consists of empty string. No log without texts - therefore check it
     */
    protected function logsAreEmpty(array $logs): bool
    {
        return '' === $logs[0]['text'];
    }

    /**
     * Get the value for the file filter key
     */
    public function fileKey(): string
    {
        return config('orchid-log.filters.fileKey', 'file');
    }

    /**
     * Get the value for the level filter key
     */
    public function levelKey(): string
    {
        return config('orchid-log.filters.levelKey', 'level');
    }

    /**
     * Get the name of default log file
     */
    public function defaultLogFile(): string
    {
        return config('orchid-log.default', 'laravel.log');
    }

    /**
     * Set file to be displayed as selected
     */
    public function setSelectedFile(?string $file = null): void
    {
        $this->logViewer->setFile(
            $file ??= $this->resolveSelectedFile()
        );
    }

    /**
     * Resolve selected file from request
     */
    public function resolveSelectedFile(): string
    {
        return $this->request->query->has($this->fileKey()) ?
            $this->logFile($this->request->query->get($this->fileKey())) :
            $this->defaultLogFile();
    }

    /**
     * Get list of all available log files
     */
    public function logFiles(): array
    {
        $excluded = config('orchid-log.filters.exclude', []);

        return array_filter($this->logViewer->getFiles(true), function (string $file, int $idx) use (&$excluded) {
            if (in_array($file, $excluded, true)) {
                unset($excluded[$idx]);

                return false;
            }

            foreach ($excluded as $excludedFile) {
                if (preg_match($excludedFile, $file)) {
                    return false;
                }
            }

            return true;
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Get log file from list by its key
     */
    public function logFile(string $key): string
    {
        return $this->logFiles()[$key];
    }

    /**
     * Clear all logs from file
     */
    public function clearFile(string $file): void
    {
        File::put($this->logViewer->pathToLogFile($file), '');
    }

    /**
     * Delete file itself
     */
    public function deleteFile(string $file): void
    {
        File::delete($this->logViewer->pathToLogFile($file));
    }
}
