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
        if (! $lieu) {
            throw new NotFoundHttpException('Lieu not found with ID: '.$id);
        }

        // Convert the Lieu model to a DTO
        return new LieuDTO(
            id: $lieu->id,
            libelle_fr: $lieu->libelle_fr,
            libelle_ar: $lieu->libelle_ar,
            etablissement: $lieu->etablissement,
            sousTypeLieu: $lieu->sous_type_lieu,
            typeLieu: $lieu->type_lieu,
            parent: $lieu->lieu,
            etat: $lieu->etat,
            capacite_theorique: $lieu->capacite_theorique,
            capacite_reelle: $lieu->capacite_reelle,
            surface: $lieu->surface_globale,
            information_details: $this->information_details($lieu)
        );
    }

    private function information_details(?Onou_cm_lieu $lieu): array
    {
        // $lieuDetails = Collection::make($lieu)
        //     ->except(['id'])
        //     ->map(function ($value, $key) {
        //         return $value;
        //     })
        //     ->toArray();
        // Add additional details
        $lieuDetails[__('livewire/tables/lieu_table.nom')] = $lieu->libelle_fr ?? '';
        $lieuDetails[__('livewire/tables/lieu_table.nom_ar')] = $lieu->libelle_ar ?? '';
        $lieuDetails[__('livewire/tables/lieu_table.etablissement')] = $lieu->etablissementLieu->full_name ?? '';
        $lieuDetails[__('livewire/tables/lieu_table.type')] = $lieu->typeLieu->full_name ?? '';
        $lieuDetails[__('livewire/tables/lieu_table.sous_type')] = $lieu->sousTypeLieu->full_name ?? '';
        $lieuDetails[__('livewire/tables/lieu_table.parent')] = $lieu->parent->full_name ?? '';
        $lieuDetails[__('livewire/tables/lieu_table.etat')] = $lieu->etatLieu->full_name ?? '';
        $lieuDetails[__('livewire/tables/lieu_table.capacite_theorique')] = $lieu->capacite_theorique ?? '';
        $lieuDetails[__('livewire/tables/lieu_table.capacite_reelle')] = $lieu->capacite_reelle ?? '';
        $lieuDetails[__('livewire/tables/lieu_table.surface')] = $lieu->surface_globale ?? '';

        return (empty($lieu)) ? [] : $lieuDetails;
    }
}
