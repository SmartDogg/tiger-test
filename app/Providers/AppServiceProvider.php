<?php

namespace App\Providers;

use App\Services\SmsApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SmsApiService::class);
    }

    public function boot(): void
    {
        //
    }
}