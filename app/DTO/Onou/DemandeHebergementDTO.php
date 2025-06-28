<?php

namespace App\DTO\Onou;

use App\DTO\BaseDTO;

class DemandeHebergementDTO extends BaseDTO
{
    public function __construct(
        public ?int $id = 0,
        public ?array $individu = null,
        public ?array $dossierInscriptionAdministrative = [],
        public ?array $demandeHebergement = [],
        public ?array $historiqueHebergement = [],
        public ?array $adressIndividue = []
    ) {}
}
