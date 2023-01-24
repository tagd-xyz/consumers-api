<?php

// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::namespace('\App\Http\V1\Controllers')->group(function () {
    Route::permanentRedirect('/', '/api/v1/status');

    Route::middleware('guest')->group(function () {
        // --------------------------------------------------------------------
        // Status
        // --------------------------------------------------------------------

        Route::get('status', 'Status@index')
            ->name('status');

        // --------------------------------------------------------------------
        // Items
        // --------------------------------------------------------------------

        Route::resource('items', 'Items')->only([
            'index', 'store', 'show', 'update',
        ]);

        Route::resource('tagds', 'Tagds')->only([
            'index',
        ]);
    });

    Route::middleware(['auth:api', 'log.user'])->group(function () {
    });
});
