<?php

namespace App\Models\Onou;

use App\Models\Scopes\Dou\AcademicyearScope;
use App\Models\Scopes\Dou\DouDashboardScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[scopedBy(AcademicyearScope::class)]
#[ScopedBy(DouDashboardScope::class)]
class vm_heb_processing_by_ru extends Model
{
    protected $table = 'onou.vm_heb_processing_by_ru';

    public static function firstOrDefault(): array
    {
        return static::first()?->toArray() ??  [
             'total' => 0,
             'accepted' => 0,
             'rejected' => 0,
             'pending' => 0];
    }

  /**
     * Get the user that owns the vm_heb_processing_by_ru
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dou(): BelongsTo
    {
        return $this->belongsTo(Onou_cm_demande::class, 'dou', 'id');
    }
      public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Onou_cm_etablissement::class, 'residence', 'id');
    }

    public function getPersontageAttribute($value)
    {
        return $this->capacite>0?($this->accepted/$this->capacite)*100:0;

    }
}
