<?php

namespace App\Models\Lmd;

use App\Models\Nc\Nomenclature;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Specialite_lmd extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'lmd.specialite_lmd';

    protected $primaryKey = 'id';

    /*
     * Get the full name of the model.
     */
    public function getFullNameAttribute(): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->ll_specialite_arabe;
        } else {
            return $this->ll_specialite;
        }
    }

    /*
     * BelongsTo relationships filiere_lmd
     */
    public function filiere_lmd(): BelongsTo
    {
        return $this->belongsTo(Filiere_lmd::class, 'filiere', 'id');
    }

    /*
     * BelongsTo relationships nc_type_specialite
     */
    public function type_specialite(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'nc_type_specialite', 'id');
    }
}
