<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Contracts;

interface Contentable
{
    public function getContent(string $field): string;
}
