<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;

interface LogServiceContract
{
    /**
     * Get paginated list of logs
     *
     * @param integer $perPage
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $options = []): LengthAwarePaginator;
}
