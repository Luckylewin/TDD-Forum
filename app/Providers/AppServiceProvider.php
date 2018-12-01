<?php

namespace App\Providers;

use App\Models\Channel;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       Carbon::setLocale('zh');
       \View::share('channels', Channel::query()->limit(10)->orderBy('created_at', 'desc')->get());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
