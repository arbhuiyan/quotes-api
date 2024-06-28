<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        Auth::viaRequest('token', function ($request) {
            if ($auth = $request->header('Authorization')) {
                @list($bearer, $token) = explode(' ', $auth);

                if (!empty($token)) {
                    return User::whereHas('tokens', function ($q) use ($token) {
                        $q->where('token', $token)
                            ->where('expires', '>', now());
                    })->first();
                }
            }
            return null;
        });
    }
}
