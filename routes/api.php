<?php

use App\Http\Controllers\Api\V1\BcvCurrencyController;
use App\Http\Controllers\Api\V1\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function ($route) {
    $route->get('/', function () {
        return response()->json([
            'message' => 'Welcome to our api application',
        ]);
    });
    $route->post('register', [\App\Http\Controllers\Api\V1\AuthenticationController::class, 'register']);
    $route->post('login', [\App\Http\Controllers\Api\V1\AuthenticationController::class, 'login']);
});

Route::name('api.v1.')->prefix('v1')->middleware([\App\Http\Middleware\JwtMiddleware::class])->group(function ($route) {
    $route->get('/user', [\App\Http\Controllers\Api\V1\AuthenticationController::class, 'getUser']);
    $route->post('/user', [\App\Http\Controllers\Api\V1\AuthenticationController::class, 'logout']);
    $route->get('update-exchange-rates', BcvCurrencyController::class)->name('update-exchange-rates');
    $route->apiResource('currencies', CurrencyController::class);

});
