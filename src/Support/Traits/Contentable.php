<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Support\Traits;

use Illuminate\Support\Str;

trait Contentable
{
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
    public function getContent(string $field): mixed
    {
        if (method_exists($this, $field)) {
            return $this->$field();
        }

        return $this->get($field);
    }

    public function __call(string $name, array $arguments): string
    {
        return $this->get($name);
    }
}
