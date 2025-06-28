<?php

namespace App\Models\Ppm;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ref_module extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'ppm.ref_module';

    public function fonctionalities(): HasMany
    {
        return $this->hasMany(ref_fonction::class, 'module');
    }
}
