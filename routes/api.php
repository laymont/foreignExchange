<?php

use App\Http\Controllers\Api\BcvCurrencyController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::name('api.')->get('/', static function () {
    return response()->json([
        'message' => 'Welcome to our api application',
    ]);
});
Route::post('register', [\App\Http\Controllers\JWTAuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\JWTAuthController::class, 'login']);

Route::middleware([\App\Http\Middleware\JwtMiddleware::class])->group(function ($route) {
    $route->get('/user', [\App\Http\Controllers\JWTAuthController::class, 'getUser']);
    $route->post('/user', [\App\Http\Controllers\JWTAuthController::class, 'logout']);
    $route->get('update-exchange-rates', BcvCurrencyController::class)->name('update-exchange-rates');

});

