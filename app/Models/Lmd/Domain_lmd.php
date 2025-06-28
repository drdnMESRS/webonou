<?php

namespace App\Models\Lmd;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Domain_lmd extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'lmd.domaine_lmd';

    public function getFullNameAttribute(): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->ll_domaine_arabe;
        } else {
            return $this->ll_domaine;
        }
    }

    /**
     * Get the filieres associated with the domain.
     */
    public function filieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Filiere_lmd::class, 'domainelmd', 'id');
    }
}
