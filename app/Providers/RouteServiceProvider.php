<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            
            Route::middleware('api')
                ->group(base_path('routes/auth.php'));
            
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/v1.php'));

            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/providers.php'));
            
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/customers.php'));
            
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/products.php'));

            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/sales_management.php'));
            
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/configuration.php'));
            
            
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/catalogues.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
