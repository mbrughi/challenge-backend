<?php

namespace App\Providers;

use App\Models\Person;
use App\Models\Persons;
use App\Policies\PersonPolicy;
use App\Policies\PersonsPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Persons::class, PersonsPolicy::class);
    }
}
