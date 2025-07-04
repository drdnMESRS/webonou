<?php

namespace App\Models\Scopes\Dou;

use App\Actions\Sessions\RoleManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ResidanceScope implements Scope
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

            $builder->where('etablissement_appartenance', $id);
        } else {
            $builder->where('onou_cm_etablissement.id', $id);
        }

    }
}
