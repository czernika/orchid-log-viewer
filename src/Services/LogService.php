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
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class LogService implements LogServiceContract
{
    protected int $perPage = 15;

    protected string $pageName = 'page';

    public function __construct(
        protected readonly LaravelLogViewer $logViewer,
        protected readonly Request $request,
    ) {
    }

    /**
     * Get paginated logs
     *
     * @return LengthAwarePaginator
     */
    public function logs(): LengthAwarePaginator
    {
        $this->setSelectedFile();
        
        $logs = $this->rawLogs();

        // Return early if there are no logs or when there are no log files at all.
        // Package creates at least one line anyway with empty text
        // but there are no errors can be without text
        if ($this->logFileDoesntExist($logs) || $this->logsAreEmpty($logs)) {
            return new LengthAwarePaginator([], 0, $this->perPage);
        }

        $logs = collect($logs)

            // Filter logs by level
            ->when($this->request->query->has($this->levelKey()), function (Collection $collection) {
                return $collection->filter(fn (array $log) =>
                    $log['level'] === $this->request->query->get($this->levelKey()));
            })

            // Convert into LogData object
            ->mapInto(LogManager::mapper());

        return new LengthAwarePaginator(
            $logs->forPage($page = Paginator::resolveCurrentPage(), $this->perPage),
            $logs->count(),
            $this->perPage,
            $page,
            ['pageName' => $this->pageName, 'path' => route(config('orchid-log.screen.route', 'platform.logs'))],
        );
    }

    /**
     * Get an array of log data
     *
     * @return array
     */
    public function rawLogs(): array
    {
        return $this->logViewer->all();
    }

    /**
     * The situation when there are lo logs at all is only one -
     * there are no any log files. To prevent error we check this data
     *
     * @return boolean
     */
    protected function logFileDoesntExist(array $logs): bool
    {
        return empty($logs);
    }

    /**
     * If there are at least on log file it will return at least one log
     * which consists of empty string. No log without texts - therefore check it
     *
     * @return boolean
     */
    protected function logsAreEmpty(array $logs): bool
    {
        return '' === $logs[0]['text'];
    }

    /**
     * Get the value for the file filter key
     *
     * @return string
     */
    public function fileKey(): string
    {
        return config('orchid-log.filters.fileKey', 'file');
    }

    /**
     * Get the value for the level filter key
     *
     * @return string
     */
    public function levelKey(): string
    {
        return config('orchid-log.filters.levelKey', 'level');
    }

    /**
     * Get the name of default log file
     *
     * @return string
     */
    public function defaultLogFile(): string
    {
        return config('orchid-log.default', 'laravel.log');
    }

    /**
     * Set file to be displayed as selected
     *
     * @param string|null $file
     * @return void
     */
    public function setSelectedFile(?string $file = null): void
    {
        $this->logViewer->setFile(
            $file ??= $this->resolveSelectedFile()
        );
    }

    /**
     * Resolve selected file from request
     *
     * @return string
     */
    public function resolveSelectedFile(): string
    {
        return $this->request->query->has($this->fileKey()) ?
            $this->logFile($this->request->query->get($this->fileKey())) :
            $this->defaultLogFile();
    }

    /**
     * Get list of all available log files
     *
     * @return array
     */
    public function logFiles(): array
    {
        return $this->logViewer->getFiles(true);
    }

    /**
     * Get log file from list by its key
     *
     * @param string $key
     * @return string
     */
    public function logFile(string $key): string
    {
        return $this->logFiles()[$key];
    }

    /**
     * Clear all logs from file
     *
     * @param string $file
     * @return void
     */
    public function clearFile(string $file): void
    {
        File::put($this->logViewer->pathToLogFile($file), '');
    }

    /**
     * Delete file itself
     *
     * @param string $file
     * @return void
     */
    public function deleteFile(string $file): void
    {
        File::delete($this->logViewer->pathToLogFile($file));
    }
}
