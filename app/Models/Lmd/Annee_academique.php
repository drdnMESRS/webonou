<?php

namespace App\Models\Lmd;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use MongoDB\Driver\Session;

class Annee_academique extends Model
{
    protected $table = 'lmd.annee_academique';
    protected $primary = 'id';


    public function fullName():Attribute{
        return Attribute::make(
            get: fn()=>$this->premiere_annee . ' / '. $this->deuxieme_annee
        );
    }

    public function scopeActiveYear(Builder $query): Builder{
        return $query->where('est_annee_en_cours', true);
    }



}
