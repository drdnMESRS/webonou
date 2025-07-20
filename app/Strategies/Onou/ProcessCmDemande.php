<?php

namespace App\Strategies\Onou;

use Illuminate\Database\Eloquent\Builder;

interface ProcessCmDemande
{
    public function process_demande(?int $id, ?array $data, ?string $action = 'accept'): bool;
 //   public function enregistrer_demande(?string $annee,?string matricule, ?array $data, ?string $action = 'accept'): bool;

    public function getView(): string;

    /**
     * Get the columns to update when processing the form.
     */
    public function formFields(?int $civility = null, ?string $action = 'accept'): array;

    public function field(?string $action = 'accept'): string;

    public function getFormView(): array;

    public function builder(): Builder;

    public function rules(?string $action = 'accept'): array;
}
