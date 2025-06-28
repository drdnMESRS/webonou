<?php

namespace App\Models\Ppm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ref_structure extends Model
{
    protected $table = 'ppm.ref_structure';

    public $timestamps = false;

    protected $primaryKey = 'id';

    /*
     * get the full name attribute
     */
    public function getFullNameAttribute()
    {
        if (app()->getLocale() === 'ar') {
            return $this->ll_structure_arabe;
        }

        return $this->ll_structure_latin;
    }

    /**
     * Get the etablissement that owns the structure.
     */
    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Ref_etablissement::class, 'etablissement');
    }
}
