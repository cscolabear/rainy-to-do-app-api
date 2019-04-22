<?php

namespace App\Providers;

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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

\DB::listen(function ($query) {
    \Log::info(
        json_encode([
            $query->sql,
            $query->bindings,
            $query->time,

        ])
    );
});

    }
}
