<?php

use Orchid\Support\Testing\DynamicTestScreen;

describe('screen', function () {
    it('shows screen when discover option is set to true', function () {
        $this->withoutExceptionHandling();

        /** @var DynamicTestScreen $screen */
        $screen = $this->screen('platform.logs');

        $response = $screen
                        ->actingAs($this->admin())
                        ->display();

        // Heading
        $response->assertSee(sprintf('<h1 class="m-0 fw-light h3 text-black">%s</h1>', __('Logs')), false);
    });
});
