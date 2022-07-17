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

    public function __construct(
        private LaravelLogViewer $logViewer,
        private Request $request,
    ) {
        $this->selected = $this->request->get('file');
    }

    /**
     * {@inheritDoc}
     */
    public function paginate(int $perPage = 15, array $options = []): LengthAwarePaginator
    {
        if ($this->selected) {
            $this->logViewer->setFile($this->selected);
        }

        $logs = collect($this->logViewer->all());

        $page = Paginator::resolveCurrentPage() ?: 1;

        if (empty($options) || ! isset($options['path'])) {
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
