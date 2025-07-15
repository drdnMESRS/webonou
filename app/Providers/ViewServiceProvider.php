<?php

namespace App\Providers;

use App\Actions\Sessions\AcademicYearSession;
use App\Models\Lmd\Annee_academique;
use App\Models\Ppm\MenuItems;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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

            // Update the session with the current academic year if empty
            if (!session()->has('activeAcademicYear_'.auth()->user()->individu)) {
                $currentAcademicYear = $academic_years->where('est_annee_en_cours', true)->first();
                (new AcademicYearSession)->update_academic_year($currentAcademicYear->id ?? null);
            }
            $view->with('academic_years', $academic_years);
        });

    }
}
