<?php

namespace App\Actions\Sessions;

use Illuminate\Support\Facades\Auth;

class RoleManagement
{
    public function get_active_role()
    {
        return Auth()->user()->activeRole;
    }

    public function get_active_role_id()
    {
        return Auth()->user()->activeRoleId;
    }

    public function get_active_role_etablissement(): ?int
    {
        $roles = Auth::user()->affectationAll;
        if (empty($roles)) {
            return null;
        }
        $activeRole = collect($roles)->filter(function ($role) {
            return $role['role']['id'] === Auth()->user()->activeRoleId;
        })->map(function ($role) {
            if (isset($role['groupe']['etablissement'])) {
                return $role['groupe']['etablissement'];
            } elseif (isset($role['structure']['etablissement'])) {
                return $role['structure']['etablissement'];
            }

            return null;
        })->first();

        return $activeRole['id'] ?? null;
    }

    public function get_active_type_etablissement(): ?string
    {
        $roles = Auth::user()->affectationAll;
        if (empty($roles)) {
            return null;
        }
        $activeRole = collect($roles)->filter(function ($role) {
            return $role['role']['id'] === Auth()->user()->activeRoleId;
        })->map(function ($role) {
            if (isset($role['groupe']['etablissement'])) {
                return $role['groupe']['etablissement'];
            } elseif (isset($role['structure']['etablissement'])) {
                return $role['structure']['etablissement'];
            }

            return null;
        })->first();

        // return the first to carecters from the etablissement identifiant
        return substr($activeRole['identifiant'] ?? '', 0, 2);
    }
}
