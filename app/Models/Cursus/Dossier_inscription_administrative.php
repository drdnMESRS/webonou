<?php

namespace App\Models\Cursus;

use App\Models\Lmd\Domain_lmd;
use App\Models\Lmd\Filiere_lmd;
use App\Models\Lmd\Niveau;
use App\Models\Lmd\Ouverture_offre_formation;
use App\Models\Ppm\Ref_etablissement;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Dossier_inscription_administrative extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'cursus.dossier_inscription_administrative';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function ouvertureOf(): BelongsTo
    {
        return $this->belongsTo(Ouverture_offre_formation::class, 'id_ouverture_of', 'id');
    }

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class, 'id_niveau', 'id');
    }

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Ref_etablissement::class, 'id_etablissement', 'id');
    }

    public function domaine(): BelongsTo
    {
        return $this->belongsTo(Domain_lmd::class, 'id_domaine', 'id');
    }

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere_lmd::class, 'id_filiere', 'id');
    }
}
