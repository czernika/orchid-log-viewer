<?php

declare(strict_types=1);

namespace Czernika\OrchidLogViewer\Contracts;

interface LogDataContract
{
    public function levelImg(): string;

    public function level(): string;

    public function levelColorClass(): string;

    public function text(): string;

    public function stack(): string;

    public function date(): string;
}
