<?php

namespace App\Models\Onou;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use YMigVal\LaravelModelCache\HasCachedQueries;
use YMigVal\LaravelModelCache\ModelRelationships;

class Onou_cm_lieu extends Model
{
    use HasCachedQueries, ModelRelationships;

    protected $table = 'onou.onou_cm_lieu';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

    public function scopePavillion(Builder $query): Builder
    {
        return $query->where('type_lieu', function ($subQuery) {
            $subQuery->select('id')
                ->from('nc.nomenclature')
                ->where('code', 'ilike', 'TYPE_LIEU_PAVILLON');
        });
    }

    public function scopeChamber(Builder $query): Builder
    {
        return $query->where('type_lieu', function ($subQuery) {
            $subQuery->select('id')
                ->from('nc.nomenclature')
                ->where('code', 'ilike', 'TYPE_LIEU_CHAMBRE');
        });
    }

    public function scopeByEtablissement(Builder $query, $id_etablissement): Builder
    {
        return $query->where('etablissement', $id_etablissement);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Onou_cm_lieu::class, 'lieu', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Onou_cm_lieu::class, 'lieu', 'id');
    }
}
