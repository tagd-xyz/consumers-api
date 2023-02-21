<?php

// use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\ExpectsActAs;
use Illuminate\Support\Facades\Route;

Route::namespace('\App\Http\V1\Controllers')->group(function () {
    Route::permanentRedirect('/', '/api/v1/status');

    Route::middleware('guest')->group(function () {
        // --------------------------------------------------------------------
        // Status
        // --------------------------------------------------------------------

        Route::get('status', 'Status@index')
            ->name('status');

        Route::post('tagds/{id}/expire', 'Tagds@expire');
        Route::post('tagds/{id}/transfer', 'Tagds@transfer');

        Route::resource('retailers', 'Retailers')->only([
            'index',
        ]);

        Route::resource('resellers', 'Resellers')->only([
            'index', 'show',
        ]);
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::get('me', 'Me@show');

        Route::middleware([ExpectsActAs::class])->group(function () {
            Route::resource('items', 'Items')->only([
                'index', 'store', 'show',
            ]);

            Route::resource('tagds', 'Tagds')->only([
                'index', 'update', 'store',
            ]);

            Route::resource('consumers', 'Consumers')->only([
                'index', 'show',
            ]);
        });
    });

    // Route::get('/me', function () {
    //     return response()->json([
    //         'message' => 'Hello from a private endpoint! You need to be authenticated to see this.',
    //         'authorized' => Auth::check(),
    //         'user' => Auth::check() ? json_decode(json_encode((array) Auth::user(), JSON_THROW_ON_ERROR), true) : null,
    //     ], 200, [], JSON_PRETTY_PRINT);
    // })->middleware(['auth0.authorize']);
});
