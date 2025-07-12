<?php

use App\Http\Controllers\SmsProxyController;
use App\Http\Middleware\RateLimitMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([RateLimitMiddleware::class . ':300'])->group(function () {
    Route::get('/getNumber', [SmsProxyController::class, 'getNumber']);
    Route::get('/getSms', [SmsProxyController::class, 'getSms']);
    Route::get('/cancelNumber', [SmsProxyController::class, 'cancelNumber']);
    Route::get('/getStatus', [SmsProxyController::class, 'getStatus']);
});