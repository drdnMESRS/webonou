<?php

namespace App\Models\Ppm;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class Ref_compte extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'ppm.ref_compte';

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->nom_utilisateur)
            ->explode('.')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => collect(Session::get('user_data_'.Auth::user()->individu)['individu'] ?? [])
                ->only(['nom_latin', 'prenom_latin'])
                ->implode(' ')
        );
    }

    private function get_activeRole()
    {
        foreach (Auth::user()->affectationAll as $role) {
            if ($role['id'] == Session::get('activeRole_'.Auth::user()->individu)) {
                return $role;
            }
        }

        return null;
    }

    public function activeRoleId(): Attribute
    {
        return Attribute::make(
            get: function () {
                $aff = $this->get_activeRole();

                return $aff['role']['id'];
            }
        );
    }

    /*
     * set the active role from the id of the affectation
     */
    public function activeRole(): Attribute
    {
        return Attribute::make(
            get: function () {
                $aff = $this->get_activeRole();

                return (is_null($aff['structure']))
                    ? $aff['role']['libelle_long_fr'].' / '.
                    $aff['groupe']['etablissement']['ll_etablissement_latin'].' / '.
                    $aff['groupe']['ll_groupe']
                    : $aff['role']['libelle_long_fr'].' / '.
                    $aff['structure']['etablissement']['ll_etablissement_latin'].' / '.
                    $aff['structure']['ll_structure_latin'];
            },
            set: fn ($value) => Session::put('activeRole_'.Auth::user()->individu, $value)
        );
    }

    public function affectationAll(): Attribute
    {

        $affactation = collect(Session::get('user_data_'.Auth::user()->individu)['individu'] ?? [])
            ->only('affectation')
            ->first();

        return Attribute::make(
            get: fn () => $affactation
        );
    }
}
