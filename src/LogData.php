<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer;

use Czernika\OrchidLogViewer\Support\Traits\Contentable;

/**
 * @method string context()
 * @method string level()
 * @method string folder()
 * @method string levelClass()
 * @method string levelImg()
 * @method string text()
 * @method string stack()
 * @method string inFile()
 * @method string date()
 */
class LogData
{
    use Contentable;

    public function __construct(
        protected readonly array $data,
    ) {
    }

    /**
     * This method is unnecessary
     *
     * Assign Bootstrap color class to colorize icon
     * because colorized things is way cooler
     */
    public function levelColorClass(): string
    {
        return match ($this->level()) {
            'debug', 'info', 'notice', 'processed' => 'text-primary',
            'warning', 'failed' => 'text-warning',
            'error', 'critical', 'alert', 'emergency' => 'text-danger',
            default => '',
        };
    }
}
