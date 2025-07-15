<?php

namespace App\Models\Ppm;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MenuItems extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'ppm.ref_permission';

    //

    /*
     * Get permission, functions, and modules for the menu items.
     */
    private function menuModules()
    {

        return Cache::remember('menu-modules_'.Auth::user()->activeRoleId, 3600, function () {
            return DB::table('ppm.ref_permission as pr')
                ->join('ppm.ref_fonction as f', 'f.id', '=', 'pr.fonction')
                ->join('ppm.ref_module as m', 'm.id', '=', 'f.module')
                ->join('ppm.ref_domaine as d', 'm.domaine', '=', 'd.id')
                ->select('m.id as module_id',
                    'm.nom as module_name',
                    'f.id as fonction_id',
                    'f.nom as fonction_name',
                    'f.url as url',
                    'f.icon as fonction_icon',
                    'pr.role as role_id')
                ->where('pr.role', Auth::user()->activeRoleId)
                ->where('pr.creer', true)
                ->where('f.active', true)
                ->where('f.new_version', true)
                ->where('d.nom', 'ilike', config('app.progres_domaine'))
                ->orderBy('f.rang', 'asc')
                ->get();
        });

    }

    /*
     * reorder the menu items by modules
     */

    public function menuItems()
    {
        $modules = $this->menuModules();

        return $modules->filter(function ($item) {
            return isset($item->module_name) && isset($item->fonction_name);
        })->groupBy('module_name')->map(function ($items) {
            return $items;
        });
    }
}
