<?php

namespace App\Providers;

use App\Auth\Providers\CachedEloquentUserProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
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

        $this->declareCacheElequentProvider();

        $this->automaticallyEagerLoadRelationships();

    }

    private function declareCacheElequentProvider(): void
    {
        auth()->provider('CachedElequent', function (Application $app, array $config) {
            return new CachedEloquentUserProvider(
                $app['hash'],
                $config['model']
            );
        });
    }

    private function automaticallyEagerLoadRelationships()
    {
        Model::automaticallyEagerLoadRelationships();
    }
}
