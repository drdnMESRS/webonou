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
        if (is_null($id) || is_null($data)) {
            throw new \InvalidArgumentException('ID and data must be provided.');
        }
        // Ensure $data is an array and contains the necessary fields
        if (! is_array($data)) {
            throw new \InvalidArgumentException('Data must be an array.');
        }
        if (empty($data)) {
            throw new \InvalidArgumentException('Data array cannot be empty.');
        }
        $demand = Onou_cm_demande::query()
            ->where('id', $id)
            ->withoutGlobalScope(DouScope::class)
            ->first();

        // dd($id, $data, $demand);
        if (! $demand) {
            throw new NotFoundHttpException("Demand with ID $id not found.");
        }
        if ($demand) {
            // Assuming $data is an associative array with the fields to update
            $demand->update($data);
            cache()->delete('History_demande_'.$demand->individu); // Clear cache for this demand

            return $demand;
        }

        return null;
    }
}
