<?php

namespace App\Models\Onou;

use App\Models\Nc\Nomenclature;
use App\Models\Scopes\Dou\DouRefuseScope;
use App\Models\Scopes\Dou\DouScope;
use App\Models\Scopes\Dou\ResidanceScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use YMigVal\LaravelModelCache\HasCachedQueries;
use YMigVal\LaravelModelCache\ModelRelationships;

#[ScopedBy(DouRefuseScope::class)]
class Onou_heb_commune_refusee extends Model
{
    use HasCachedQueries, ModelRelationships;

    protected $table = 'onou.onou_heb_commune_refusee';

    protected $primaryKey = 'id';

    public $timestamps = false;

}
