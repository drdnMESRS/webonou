<?php

namespace App\Actions\Pages\Dossier_demande_Hebergement;

use App\Models\Onou\Onou_cm_demande;
use App\Models\Scopes\Dou\DouScope;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateDemandById
{

    public function handle($id, $data)
    {
        // Logic to update the demand by ID
        // This could involve fetching the demand from the database,
        // updating its properties with the provided data, and saving it.

        // Example:
        $demand = Onou_cm_demande::query()
            ->where('id', $id)
            ->withoutGlobalScope(DouScope::class)
            ->first();
        //dd($id, $data, $demand);
        if (!$demand) {
            throw new NotFoundHttpException("Demand with ID $id not found.");
        }
        if ($demand) {
            // Assuming $data is an associative array with the fields to update
            $demand->update($data);
            cache()->delete('History_demande_' . $demand->individu); // Clear cache for this demand
            return $demand;
        }

        return null; // or throw an exception if not found
    }

}
