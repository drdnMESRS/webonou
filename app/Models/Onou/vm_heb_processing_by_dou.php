<?php

namespace App\Models\Onou;

use App\Models\Scopes\Dou\AcademicyearScope;
use App\Models\Scopes\Dou\DouDashboardScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[scopedBy(AcademicyearScope::class)]
#[ScopedBy(DouDashboardScope::class)]
class vm_heb_processing_by_dou extends Model
{
    protected $table = 'onou.vm_heb_processing_by_dou';
}
