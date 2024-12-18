<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::define('admin-access', function(User $user) {
            return $user->role == "admin";
        });

        Gate::define('manage-employee', function(User $user, Employee $employee) {
            return $user->role == "admin" || ($user->role == "manager" && $user->company_id == $employee->company_id);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Passport::loadKeysFrom(__DIR__.'/../secrets/oauth');
    }
}
