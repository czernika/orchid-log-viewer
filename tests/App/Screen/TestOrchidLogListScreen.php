<?php

declare(strict_types=1);

namespace Tests\App\Screen;

use Czernika\OrchidLogViewer\Screen\OrchidLogListScreen;

class TestOrchidLogListScreen extends OrchidLogListScreen
{
    public function name(): ?string
    {
        return 'Test logs';
    }
}
