<?php

namespace App\Providers;

use App\Models\Lmd\Annee_academique;
use App\Models\Ppm\MenuItems;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        View::composer(['components.layouts.app.sidebar'], function ($view) {
            $menuItems = (new MenuItems)->menuItems();
            $view->with('menuItems', $menuItems);
        });

        View::composer(['components.layouts.partials.academic_year'], function ($view) {
            $academic_years = Cache::rememberForever('academic_years', function () {
                    return Annee_academique::query()->orderByDesc('premiere_annee')->get();
            });
            $view->with('academic_years', $academic_years);
        });



    }
}
