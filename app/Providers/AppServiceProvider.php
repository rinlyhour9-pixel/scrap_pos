<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        date_default_timezone_set('Asia/Phnom_Penh');
        Paginator::useBootstrapFive();
    }
}
