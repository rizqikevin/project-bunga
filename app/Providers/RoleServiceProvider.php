<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckRole;

class RoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->app['router']->aliasMiddleware('role', CheckRole::class);
    }
}
