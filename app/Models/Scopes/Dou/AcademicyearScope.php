<?php

namespace App\Models\Scopes\Dou;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AcademicyearScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('annee_academique', (new \App\Actions\Sessions\AcademicYearSession)->get_academic_year());
    }
}
