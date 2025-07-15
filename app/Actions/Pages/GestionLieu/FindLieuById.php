<?php

namespace App\Actions\Pages\GestionLieu;

use App\DTO\Onou\LieuDTO;
use App\Models\Onou\Onou_cm_lieu;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FindLieuById
{

    public function handle(int $id): LieuDTO
    {
        // Validate the ID if necessary
        if ($id <= 0) {
            throw new NotFoundHttpException('Invalid Lieu ID provided');
        }

        // Assuming you have a Lieu model that interacts with the database
        $lieu = Onou_cm_lieu::findOrFail($id)->withRelationshipAutoloading();

        // If the Lieu is not found, throw an exception
        if (!$lieu) {
            throw new NotFoundHttpException('Lieu not found with ID: ' . $id);
        }


        // Convert the Lieu model to a DTO
        return new LieuDTO(
            id: $lieu->id,
            libelle_fr: $lieu->libelle_fr,
            libelle_ar: $lieu->libelle_ar,
            information_details: $this->information_details($lieu)
        );
    }


    private function information_details(?Onou_cm_lieu $lieu): array
    {
        $lieuDetails = Collection::make($lieu)
            ->except(['id'])
            ->map(function ($value, $key) {
                return $value;
            })
            ->toArray();
        // Add additional details

        $lieuDetails['sous_type_lieu'] = $lieu->sousTypeLieu->full_name ?? '';
        $lieuDetails['type_lieu'] = $lieu->typeLieu->full_name ?? '';
        $lieuDetails['etablissement'] = $lieu->etablissementLieu->full_name ?? '';
        $lieuDetails['lieu'] = $lieu->parent->full_name ?? '';


        return (empty($lieu)) ? [] : $lieuDetails;
    }


}
