<?php

namespace App\Actions\Pages\Dossier_demande_Hebergement;

use App\Models\Onou\Onou_cm_demande;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateDemand
{
    public function handle($data)
    {

        // Ensure $data is an array and contains the necessary fields
        if (! is_array($data)) {
            throw new \InvalidArgumentException('Data must be an array.');
        }
        if (empty($data)) {
            throw new \InvalidArgumentException('Data array cannot be empty.');
        }
        $demand = Onou_cm_demande::create($data);
        // dd($id, $data, $demand);
        if (! $demand) {
            throw new NotFoundHttpException("Demand cant\'t create");

            return null;
        }
        cache()->delete('History_demande_'.$demand->individu); // Clear cache for this demand

        return $demand;
    }
}
