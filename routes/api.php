<?php

use App\Http\Controllers\Api\V1\BcvCurrencyController;
use App\Http\Controllers\Api\V1\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::name('api.v1.')->prefix('v1')->group(function ($route) {
    $route->get('/', function () {
        return response()->json([
            'message' => 'Welcome to our api application',
        ]);
    });
    $route->post('register', [\App\Http\Controllers\Api\V1\AuthenticationController::class, 'register'])->name('register');
    $route->post('login', [\App\Http\Controllers\Api\V1\AuthenticationController::class, 'login'])->name('login');
});

Route::name('api.v1.')->prefix('v1')->middleware([\App\Http\Middleware\JwtMiddleware::class])->group(function ($route) {
    $route->get('/user', [\App\Http\Controllers\Api\V1\AuthenticationController::class, 'getUser'])->name('user');
    $route->post('/user', [\App\Http\Controllers\Api\V1\AuthenticationController::class, 'logout'])->name('logout');

    $route->get('update-exchange-rates', BcvCurrencyController::class)->name('update-exchange-rates');

    $route->apiResource('currencies', CurrencyController::class);
});
