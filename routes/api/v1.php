<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'api.v1.',
    // 'auth.api-key'
    // 'middleware' => ['auth:api'],
], function () {

    Route::get('/', function() {
        return ['Laravel' => app()->version()];
    })->name('index');

});

require __DIR__.'/../auth.php';
