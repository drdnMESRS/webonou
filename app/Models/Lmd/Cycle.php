<?php

namespace App\Models\Lmd;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cycle extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'lmd.cycle';

    protected $primaryKey = 'id';

    /*
     * Get the full name of the cycle.
     */
    public function getFullNameAttribute()
    {
        if (app()->getLocale() === 'ar') {
            return $this->libelle_long_ar ?? $this->libelle_long_lt;
        } else {
            return $this->libelle_long_lt;
        }
    }
}
