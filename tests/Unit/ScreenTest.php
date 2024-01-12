<?php

use Illuminate\Support\Facades\Route;

uses()->group('unit.screen');

describe('screen', function () {
    it('does register route when discover option is set to true', function () {
        expect(Route::has('platform.logs'))->toBeTrue();
    });

    it('does not register route when discover option is set to false')->todo();

    it('registers route under default name')->todo();

    it('can change route name')->todo();

    it('registers route under custom url')->todo();

    it('can change route url')->todo();
});
