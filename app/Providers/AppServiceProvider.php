<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        try {

            $data['gs'] = GeneralSetting::first();
        

            view::share($data);

        } catch (\Exception $e) {

            return $e->getMessage();
        }
        //compose all the views....
        view()->composer('*', function ()
        {
            $authUser = Auth::user();
        });

        view()->share(['appUrl' => \Request::root(),'publicPath' => \Request::root()]);
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
