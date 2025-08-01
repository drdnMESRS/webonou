<?php

namespace App\DTO\Onou;

use App\DTO\BaseDTO;

class DemandeHebergementDTO extends BaseDTO
{
    public function __construct(
        public ?int $id = 0,
        public ?bool $frais_hebregement_pay = false,
        public ?int $actual_page = null,
        public ?array $individu = null,
        public ?array $dossierInscriptionAdministrative = [],
        public ?array $dossierInscriptionDoctorant = [],
        public ?array $demandeHebergement = [],
        public ?array $historiqueHebergement = [],
        public ?array $historiqueInscription = [],
        public ?array $adressIndividue = [],
        public ?int $id_dia = null,
        public ?int $id_fnd = null,
        public ?int $id_individu = 0,
          public ?string $photo = null,
          public ?string $year=null,
        public ?int $civilite = null,
        public ?bool $cles_remis = null,
        public ?string $cles_remis_at = null,

    ) {}
}
