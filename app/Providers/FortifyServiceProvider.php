<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class FortifyServiceProvider extends BaseServiceProvider
{

    public function register(): void
    {
        //
    }
    public function boot()
    {
        // Fortify features are disabled or handled via ExternalLoginController
    }
}
