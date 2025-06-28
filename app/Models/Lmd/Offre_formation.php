<?php

namespace App\Models\Lmd;

use App\Models\Nc\Nomenclature;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Offre_formation extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'lmd.offre_formation';

    /**
     * Get the full name of the offer.
     */
    public function getFullNameAttribute()
    {
        if (app()->getLocale() === 'ar') {
            return $this->libelle_long_ar ?? $this->libelle_long_fr;
        } else {
            return $this->libelle_long_fr;
        }
    }

    public function domaine(): BelongsTo
    {
        return $this->belongsTo(Domain_lmd::class, 'id_domaine', 'id');
    }

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere_lmd::class, 'id_filiere', 'id');
    }

    public function specialite(): BelongsTo
    {
        return $this->belongsTo(Specialite_lmd::class, 'id_specialite', 'id');
    }

    public function typeFormation(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'id_type_formation', 'id');
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class, 'id_cycle', 'id');
    }
}
