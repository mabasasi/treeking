<?php

namespace App\Providers;

use App\Validators\DatabaseValidator;
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
        // extend validator
        \Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new DatabaseValidator($translator, $data, $rules, $messages);
        });

        // TODO 消し忘れないように！
        \DB::enableQueryLog();
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
