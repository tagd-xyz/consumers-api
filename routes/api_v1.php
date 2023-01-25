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
            'index', 'store', 'show',
        ]);

        Route::resource('tagds', 'Tagds')->only([
            'index', 'update', 'store',
        ]);

        Route::post('tagds/{id}/expire', 'Tagds@expire');
        Route::post('tagds/{id}/transfer', 'Tagds@transfer');

        Route::resource('consumers', 'Consumers')->only([
            'index', 'show',
        ]);

        Route::resource('retailers', 'Retailers')->only([
            'index',
        ]);

        Route::resource('resellers', 'Resellers')->only([
            'index', 'show',
        ]);
    });

    Route::middleware(['auth:api', 'log.user'])->group(function () {
    });
});
