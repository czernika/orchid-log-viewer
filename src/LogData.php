<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer;

use Czernika\OrchidLogViewer\Contracts\Contentable;
use Illuminate\Support\Str;

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
class LogData implements Contentable
{
    public function __construct(
        protected readonly array $data,
    ) {
    }

    /**
     * Get any field from data array
     */
    public function get(string $field): string
    {
        $name = Str::of($field)->snake()->value();

        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return '';
    }

    /**
     * Use the power of Orchid - it uses `getContent` method under the hood to resolve Model attributes and relations.
     * We're simulating this approach in order to use shortcut syntax without using `render` method
     */
    public function getContent(string $field): string
    {
        return $this->get($field);
    }

    /**
     * This method is unnecessary
     *
     * Assign Bootstrap color class to colorize icon
     * because colorized things is way cooler
     */
    public function bootstrapClass(): string
    {
        return match ($this->level()) {
            'debug', 'info', 'notice', 'processed' => 'text-primary',
            'warning', 'failed' => 'text-warning',
            'error', 'critical', 'alert', 'emergency' => 'text-danger',
            default => '',
        };
    }

    public function __call(string $name, array $arguments): string
    {
        return $this->get($name);
    }
}
