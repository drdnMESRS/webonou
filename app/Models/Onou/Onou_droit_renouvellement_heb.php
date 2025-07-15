<?php

namespace App\Models\Onou;

use App\Models\Ppm\Ref_Individu;
use App\Models\Scopes\Dou\DouRefuseScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YMigVal\LaravelModelCache\HasCachedQueries;
use YMigVal\LaravelModelCache\ModelRelationships;

#[ScopedBy(DouRefuseScope::class)]
class Onou_droit_renouvellement_heb extends Model
{
    use HasCachedQueries, ModelRelationships;

    protected $table = 'onou.onou_droit_renouvellement_heb';

    protected $primaryKey = 'id';

    public $timestamps = false;


}
