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
       // Carbon::setLocale('zh');
       view()->composer('*', function ($view) {
           $channels = \Cache::rememberForever('channels', function(){
                return Channel::query()->limit(10)->orderBy('created_at', 'desc')->get();
           });
           $view->with('channels', $channels);
       });

       // 扩展自定义的验证方法
        \Validator::extend('spamfree','App\Rules\SpamFree@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
