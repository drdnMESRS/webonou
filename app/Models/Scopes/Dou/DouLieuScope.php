<?php

namespace App\Models\Scopes\Dou;

use App\Actions\Sessions\RoleManagement;
use App\Models\Onou\Onou_cm_etablissement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Cache;

class DouLieuScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $type = app(RoleManagement::class)->get_active_type_etablissement();
        $id = app(RoleManagement::class)->get_active_role_etablissement();

        // find the active role and its etablissement
        if (app(RoleManagement::class)->get_active_type_etablissement() == 'DO') {
            $children = Cache::remember(
                'onou_cm_etablissement_children_' . $id,
                60 * 60 * 24, // Cache for 24 hours
                function () use ($id) {
                    return Onou_cm_etablissement::query()
                        ->where('etablissement_appartenance', $id)
                        ->pluck('id')
                        ->toArray();
                }
            );
            $builder->where(function ($query) use ($id, $children) {
                $query->where('etablissement', $id)
                    ->orWhereIn('etablissement', $children);
            });
        }
    }
}
