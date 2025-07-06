<?php

namespace App\Models\Onou;

use App\Models\Scopes\Dou\DouRefuseScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YMigVal\LaravelModelCache\HasCachedQueries;
use YMigVal\LaravelModelCache\ModelRelationships;


class Onou_cm_affectation_individu extends Model
{
    use HasCachedQueries, ModelRelationships;

    protected $table = 'onou.onou_cm_affectation_individu';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $guarded = [];

    public function lieu():BelongsTo
    {
        return $this->belongsTo(Onou_cm_etablissement::class, 'lieu', 'id');
    }
}
