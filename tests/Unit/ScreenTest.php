<?php

use Illuminate\Support\Facades\Route;

describe('screen', function () {
    it('does register route when discover option is set to true', function () {
        expect(Route::has('platform.logs'))->toBeTrue();
    });

    it('does not register route when discover option is set to false')->todo();
});
