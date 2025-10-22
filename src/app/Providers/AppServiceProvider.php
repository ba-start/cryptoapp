<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */



    public function boot()
    {
        View::composer('*', function ($view) {
            $user = null;
            $token = Session::get('token');

            if ($token) {
                try {
                    $user = JWTAuth::setToken($token)->authenticate();
                } catch (\Exception $e) {
                    Session::forget('token');
                }
            }

            $view->with('user', $user);
        });
    }

}