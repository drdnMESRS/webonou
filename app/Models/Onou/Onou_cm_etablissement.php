<?php

namespace App\Models\Onou;

use App\Models\Nc\Nomenclature;
use App\Models\Scopes\Dou\ResidanceScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YMigVal\LaravelModelCache\HasCachedQueries;
use YMigVal\LaravelModelCache\ModelRelationships;

#[ScopedBy(ResidanceScope::class)]
class Onou_cm_etablissement extends Model
{
    use HasCachedQueries, ModelRelationships;

    protected $table = 'onou.onou_cm_etablissement';

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * get the full name of the etablissement.
     */
    public function getFullNameAttribute()
    {
        if (app()->getLocale() === 'ar') {
            return $this->denomination_ar;
        }

        return $this->denomination_fr;
    }

    /**
     * Get the etablissement associated with the Onou_cm_etablissement.
     */
    public function etablissement()
    {
        return $this->belongsTo('App\Models\Ppm\Ref_etablissement', 'id', 'id');
    }

    /**
     * Get the etablissement appartenance with the ppm_ref_etablissement.
     */
    public function appartenance()
    {
        return $this->belongsTo('App\Models\Ppm\Ref_etablissement', 'etablissement_appartenance', 'id');
    }

    /**
     * Get the type based NC nomenclature.
     */
    public function type_nc(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'type_resid', 'id');
    }

    /**
     * Get the type based NC nomenclature.
     */
    public function type_strecture(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'type', 'id');
    }

    /**
     * Get the type based NC nomenclature.
     */
    public function etat_strecture(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'etat', 'id');
    }
}
