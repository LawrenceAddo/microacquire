<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

use App\User;
use App\Profiles;
use App\SellingProps;

class AppServiceProvider extends ServiceProvider
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        /*
        SellingProps::created(function ($seller) {

            $user = $seller->owner;

            if ($user->type == 0) {
                if (!$user->profile) {
                    $user->profile = new Profiles(['user_id' => $seller->user_id]);
                }

                $user->profile->company_name = $seller->name;
                $user->profile->company_description = $seller->description;
                $user->profile->save();
            }

        });

        SellingProps::updated(function ($seller) {

            $user = $seller->owner;
            
            if ($user->type == 0) {
                if (!$user->profile) {
                    $user->profile = new Profiles(['user_id' => $seller->user_id]);
                }

                $user->profile->company_name = $seller->name;
                $user->profile->company_description = $seller->description;
                $user->profile->company_description = $seller->description;
                $user->profile->save();
            }

        });
        */
    }
}
