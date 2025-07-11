<?php

namespace App\Actions\Pages\Dossier_demande_Hebergement;

use App\Actions\Sessions\RoleManagement;
use App\Models\Onou\Onou_cm_affectation_individu;
use Illuminate\Support\Carbon;

class CreateAffectationIndividu
{
    public function handle(int $individuId, int $chambreId): int
    {
        // Validate the inputs
        if ($individuId <= 0 || $chambreId <= 0) {
            throw new \InvalidArgumentException('Invalid individu or chambre ID provided.');
        }
        $data = [
            'individu' => $individuId,
            'lieu' => $chambreId,
            'date_debut' => now(),
            'date_fin' => Carbon::now()->addMonths(12),
            'nature_affectation' => 699276,
            'affected_to' => 698992,
            'onouetablissement' => app(RoleManagement::class)->get_active_role_etablissement(), // Assuming the end date is not set initially
        ];

        // test if the affectation already exists
        $existingAffectation = Onou_cm_affectation_individu::updateOrCreate(
            ['individu' => $individuId, 'lieu' => $chambreId],
            $data
        );

        return $existingAffectation->id; // Return true if the affectation was successfully created
    }
}
