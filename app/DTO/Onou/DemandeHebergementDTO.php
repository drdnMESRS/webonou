<?php

namespace App\DTO\Onou;

use App\DTO\BaseDTO;
use Carbon\Carbon;

class DemandeHebergementDTO extends BaseDTO
{
    public function __construct(
        public ?int $id = 0,
        public ?int $actual_page = null,
        public ?array $individu = null,
        public ?array $dossierInscriptionAdministrative = [],
        public ?array $demandeHebergement = [],
        public ?array $historiqueHebergement = [],
        public ?array $historiqueInscription = [],
        public ?array $adressIndividue = [],
        public ?int $id_dia = 0,
        public ?int $id_individu = 0,
        public ?bool $cles_remis = null,
        public ?string $cles_remis_at = null,

    ) {}
}
