<?php

namespace App\Models\doctorat;

use App\Models\Nc\Nomenclature;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Suiv_fichier_national_doctorant extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'doctorat.suivi_fichier_national_doctorant';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function fichier_national_doctorant(): BelongsTo
    {
        return $this->belongsTo(fichier_national_doctorant::class, 'id_fnd', 'id');
    }

    public function situation(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'id_situation', 'id');
    }
}
