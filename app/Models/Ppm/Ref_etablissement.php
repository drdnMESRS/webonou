<?php

namespace App\Models\Ppm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ref_etablissement extends Model
{

    protected $table = 'ppm.ref_etablissement';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * Get the structure that owns the etablissement.
     */
    public function structure():HasMany
    {
        return $this->hasMany(Ref_structure::class, 'etablissement');
    }

}
