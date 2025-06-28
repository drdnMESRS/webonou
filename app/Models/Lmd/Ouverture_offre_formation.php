<?php

namespace App\Models\Lmd;

use App\Models\Ppm\Ref_etablissement;
use App\Models\Ppm\Ref_structure;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Ouverture_offre_formation extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'lmd.ouverture_offre_formation';

    /*
     * Belongs to an offre formation.
     */
    public function offreFormation(): BelongsTo
    {
        return $this->belongsTo(Offre_formation::class, 'id_offre_formation', 'id');
    }

    /*
     * Belongs to a etablissement.
     */
    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Ref_etablissement::class, 'id_etablissement', 'id');
    }

    /*
     * Belongs to a structure.
     */

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Ref_structure::class, 'id_structure', 'id');
    }
}
