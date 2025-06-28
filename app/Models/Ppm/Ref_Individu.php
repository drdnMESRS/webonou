<?php

namespace App\Models\Ppm;

use App\Models\Nc\Nomenclature;
use App\Models\Scopes\Ppm\IndividuScope;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[ScopedBy(IndividuScope::class)]

class Ref_Individu extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'ppm.ref_individu';

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * Get the full name of the individual.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if (app()->getLocale() === 'ar') {
            return $this->nom_arabe.' '.$this->prenom_arabe;
        }

        return $this->nom_latin.' '.$this->prenom_latin;
    }

    public function getPereFullNameAttribute()
    {
        if (app()->getLocale() === 'ar') {
            return $this->nom_arabe.' '.$this->prenom_pere_arabe ?? ' ';
        }

        return $this->nom_latin.' '.$this->prenom_pere_latin ?? ' ';
    }

    public function getMereFullNameAttribute()
    {
        if (app()->getLocale() === 'ar') {
            return $this->nom_mere_arabe.' '.$this->prenom_mere_arabe ?? '';
        }

        return $this->nom_mere_latin.' '.$this->prenom_mere_latin ?? ' ';
    }

    public function civility(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'civilite', 'id');
    }

    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'groupe_sanguin', 'id');
    }

    public function individualType(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'type_individu', 'id');
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'nationalite', 'id');
    }

    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'situation_familiale', 'id');
    }

    public function nationalServiceStatus(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'situation_service_national', 'id');
    }
}
