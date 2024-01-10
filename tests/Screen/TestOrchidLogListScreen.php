<?php

declare(strict_types=1);

namespace Tests\Screen;

use Czernika\OrchidLogViewer\Screen\OrchidLogListScreen;

class TestOrchidLogListScreen extends OrchidLogListScreen
{
    public function name(): ?string
    {
        return 'Test logs';
    }
}
