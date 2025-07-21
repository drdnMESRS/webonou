<?php

namespace App\Models\Onou;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YMigVal\LaravelModelCache\HasCachedQueries;
use YMigVal\LaravelModelCache\ModelRelationships;

class Onou_cm_affectation_individu extends Model
{
    use HasCachedQueries, ModelRelationships;

    protected $table = 'onou.onou_cm_affectation_individu';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

    public function lieuaffectation(): BelongsTo
    {
        return $this->belongsTo(Onou_cm_lieu::class, 'lieu', 'id');
    }
}
