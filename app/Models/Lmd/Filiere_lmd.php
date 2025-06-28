<?php

namespace App\Models\Lmd;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Filiere_lmd extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'lmd.filiere_lmd';

    /*
    * Get the full name of the model.
    */
    public function getFullNameAttribute(): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->ll_filiere_arabe;
        } else {
            return $this->ll_filiere;
        }
    }

    /*
     * BelongsTo relationships domain_lmd
     */
    public function domain_lmd(): BelongsTo
    {
        return $this->belongsTo(Domain_lmd::class, 'domainelmd', 'id');
    }

    /*
     * Specialities associated with the filiere.
     */
    public function specialites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Specialite_lmd::class, 'filiere', 'id');
    }
}
