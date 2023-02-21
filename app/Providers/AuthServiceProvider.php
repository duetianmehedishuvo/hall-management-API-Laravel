<?php

namespace App\Providers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {


        $this->app['auth']->viaRequest('api', function ($request) {
            $access_token = str_replace('Bearer ', '', $request->header('Authorization'));
        
            $key = env('TOKEN_KEY');
            try {
                $decoded = JWT::decode($access_token, new Key($key, 'HS256'));
                return new User();
            } catch (\Exception $e) {
                return null;
            }


        });
    }
}
