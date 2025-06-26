<?php

namespace App\DTO\Onou;

class ResidanceDTO extends \App\DTO\BaseDTO
{
    // create a DTO class for the residence
    public function __construct(
        public ?int $id = null,
        public ?string $identifiant = null,
        public ?string $full_name = null,
        public ?string $type_nc_full_name = null,
        public ?string $appartenance = null,
        public ?string $etat_strecture_full_name = null,
        public ?string $date_creation = null,
        public ?string $date_ouverture = null,
        public ?string $numero_inscription_domaniale = null,
        public ?string $date_inscription_domaniale = null,
        public ?int    $capacite_theorique = null,
        public ?int    $capacite_reel = null,
        public ?int    $places_disponibles_garcons = null,
        public ?int    $places_disponibles_filles = null,
        public ?string $surface_globale = null,
        public ?string $surface_batie = null,
        public ?string $consistance = null,

    ) {}


}
