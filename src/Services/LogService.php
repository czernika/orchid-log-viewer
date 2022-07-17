<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Services;

use Czernika\OrchidLogViewer\Contracts\LogServiceContract;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;

class LogService implements LogServiceContract
{

    private ?string $selected = null;

    private ?string $level = null;

    public function __construct(
        private LaravelLogViewer $logViewer,
        private Request $request,
    ) {
        $this->setSelectedFile();
        $this->setSelectedLevel();
    }

    /**
     * Get name of selected file
     *
     * @return string
     */
    public function selected(): string
    {
        return $this->selected;
    }

    /**
     * Set selected file
     *
     * @return void
     */
    private function setSelectedFile(): void
    {
        $this->selected = $this->request->get('file') ?? head($this->logViewer->getFiles(true));
    }

    /**
     * Set selected level
     *
     * @return void
     */
    private function setSelectedLevel(): void
    {
        $this->level = $this->request->get('level') ?? 'error';
    }

    /**
     * Get paginated list of logs
     *
     * @param integer $perPage
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $options = []): LengthAwarePaginator
    {
        if ($this->selected) {
            $this->logViewer->setFile($this->selected);
        }

        $logs = collect($this->logViewer->all())
                    ->when($this->level, fn($collection) => $collection->filter(fn($log) => $log['level'] === $this->level));

        $page = Paginator::resolveCurrentPage() ?: 1;

        if (empty($options) || !isset($options['path'])) {
            $options['path'] = route(Route::current()->getName());
        }

        $paginator = new LengthAwarePaginator(
            $logs->forPage($page, $perPage),
            $logs->count(),
            $perPage,
            $page,
            $options,
        );

        return $paginator;
    }
}
