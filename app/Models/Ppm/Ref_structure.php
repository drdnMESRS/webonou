<?php

namespace App\Models\Ppm;

use App\Models\Scopes\Dou\ResidanceScope;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Ref_structure extends Model
{

    protected $table = 'ppm.ref_structure';

    public $timestamps = false;

    protected $primaryKey = 'id';

    /**
     * Get the etablissement that owns the structure.
     */
    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Ref_etablissement::class, 'etablissement');
    }

}
