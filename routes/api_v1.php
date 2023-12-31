<?php

use Illuminate\Support\Facades\Route;

Route::namespace('\App\Http\V1\Controllers')->group(function () {
    Route::permanentRedirect('/', '/api/v1/status');

    Route::middleware('guest')->group(function () {
        Route::get('status', 'Status@index')
            ->name('status');
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', 'Me@show');

        Route::resource('notifications', 'Notifications')->only([
            'index',
            'update',
        ]);

        Route::resource('items', 'Items')->only([
            'index', 'show',
        ]);

        Route::post('tagds/{id}/enableForResale', 'Tagds@enableForResale');
        Route::post('tagds/{id}/disableForResale', 'Tagds@disableForResale');
        Route::resource('tagds', 'Tagds')->only([
            'index', 'show',
        ]);

        Route::post('resale-access-requests/{id}/reject', 'ResaleAccessRequests@reject');
        Route::post('resale-access-requests/{id}/approve', 'ResaleAccessRequests@approve');
        Route::resource('resale-access-requests', 'ResaleAccessRequests')->only([
            'index', 'show',
        ]);

        Route::group(['namespace' => 'Ref'], function () {
            Route::prefix('ref')->group(function () {
                Route::resource('item-types', 'ItemTypes')->only([
                    'index',
                ]);
            });
        });
    });
});
