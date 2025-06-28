<?php

namespace App\Models\Nc;

use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    protected $table = 'nc.nomenclature';

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * Get the full name of the nomenclature.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if (app()->getLocale() === 'ar') {
            return $this->libelle_long_ar;
        }

        return $this->libelle_long_fr;
    }

    /*
     * retuns all the nomenclatures belonging to a specific list based on the list id
     */
    public function scopeByListId($query, $listId)
    {
        return $query->where('id_list', $listId);
    }
}
