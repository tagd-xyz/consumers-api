<?php

// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::namespace('\App\Http\V1\Controllers')->group(function () {
    Route::permanentRedirect('/', '/api/v1/status');

    Route::middleware('guest')->group(function () {
        // --------------------------------------------------------------------
        // Status
        // --------------------------------------------------------------------

        Route::get('status', 'StatusController@index')
            ->name('status');
    });

    Route::middleware(['auth:api', 'log.user'])->group(function () {
    });
});
