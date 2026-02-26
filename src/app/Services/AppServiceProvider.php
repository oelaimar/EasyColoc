<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider
{
    public function boot(): void
    {
        Gate::define('admin-only', function(User $user){
           return $user->is_global_admin === true;
        });
    }
}
