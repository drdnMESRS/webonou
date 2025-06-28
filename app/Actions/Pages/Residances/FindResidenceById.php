<?php

namespace App\Actions\Pages\Residances;

use App\DTO\Onou\ResidanceDTO;
use App\Models\Onou\Onou_cm_etablissement;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FindResidenceById
{
    public function handle(?int $residenceId): ResidanceDTO
    {
        // Validate the residence ID
        if (is_null($residenceId) || $residenceId <= 0) {
            throw new NotFoundHttpException('Invalid residence ID provided');
        }
        // For example:
        $residence = Onou_cm_etablissement::find($residenceId)
            ->withRelationshipAutoloading();

        if (! $residence) {
            throw new NotFoundHttpException('Residence not found with ID');
        }

        // put this in a DTO
        $residenceDTO = (new ResidanceDTO)
            ->FromArray([
                'id' => $residence->id,
                'identifiant' => $residence->etablissement->identifiant,
                'full_name' => $residence->full_name,
                'type_nc_full_name' => $residence->type_nc->full_name,
                'appartenance' => $residence->appartenance->ll_etablissement_latin,
                'etat_strecture' => $residence->etat_strecture->full_name ?? '',
                'date_creation' => $residence->date_creation ?? '',
                'date_ouverture' => $residence->date_ouverture ?? '',
                'numero_inscription_domaniale' => $residence->num_inscription_domaniale ?? '',
                'date_inscription_domaniale' => $residence->date_inscription_domaniale ?? '',
                'capacite_theorique' => $residence->capacite_theorique ?? 0,
                'capacite_reel' => $residence->capacite_relle ?? 0,
                'places_disponibles_garcons' => $residence->places_disponibles_g ?? 0,
                'places_disponibles_filles' => $residence->places_disponibles_f ?? 0,
                'surface_globale' => $residence->surface_globale ?? '',
                'surface_batie' => $residence->surface_batie ?? '',
                'consistance' => $residence->consistance ?? '',

            ]);

        // Return the DTO
        return $residenceDTO;

    }
}
