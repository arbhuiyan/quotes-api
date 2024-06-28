<?php

namespace App\Providers;

use App\Quotes\QuotesManager;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class QuotesServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->singleton('quotes', function ($app) {
            return new QuotesManager($app);
        });
    }

    public function provides()
    {
        return ['quotes'];
    }
}