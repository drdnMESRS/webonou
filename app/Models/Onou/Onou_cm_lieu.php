<?php

namespace App\Models\Onou;

use App\Models\Nc\Nomenclature;
use App\Models\Scopes\Dou\DouLieuScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use YMigVal\LaravelModelCache\HasCachedQueries;
use YMigVal\LaravelModelCache\ModelRelationships;


#[ScopedBy(DouLieuScope::class)]
class Onou_cm_lieu extends Model
{
    use HasCachedQueries, ModelRelationships;

    protected $table = 'onou.onou_cm_lieu';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

    /**
     * Get the full name of the lieu.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        if (app()->getLocale() === 'ar') {
            if (!empty($this->libelle_ar)) {
                return $this->libelle_ar;
            }
            return $this->libelle_fr;
        }

        return $this->libelle_fr;
    }

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


    public function sousTypeLieu(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'sous_type_lieu', 'id')
            ->withDefault([
                'id' => null,
                'libelle_long_ar' => '',
                'libelle_long_fr' => '',
            ]);
    }
    public function typeLieu(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'type_lieu', 'id');
    }

    public function etablissementLieu(): BelongsTo
    {
        return $this->belongsTo(Onou_cm_etablissement::class, 'etablissement', 'id')
            ->withDefault([
                'id' => null,
                'denomination_ar' => '',
                'denomination_fr' => '',
            ])
            ->withoutGlobalScope(DouLieuScope::class);
    }

    public function scopeByEtablissement(Builder $query, $id_etablissement): Builder
    {
        return $query->where('etablissement', $id_etablissement)
            ;
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
