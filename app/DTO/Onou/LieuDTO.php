<?php

namespace App\DTO\Onou;

use App\DTO\BaseDTO;

class LieuDTO extends BaseDTO
{

    public function __construct(
        public ?int $id = null,
        public ?string $libelle_fr = null,
        public ?string $libelle_ar = null,
        public ?int $etablissement = null,
        public ?int $typeLieu = null,
        public ?int $sousTypeLieu = null,
        public ?int $parent = null,
        public ?array $information_details = null,
    ) {
    }

    public function getFullNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->libelle_ar : $this->libelle_fr;
    }

}
