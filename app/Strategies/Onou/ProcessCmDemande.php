<?php

namespace App\Strategies\Onou;

use Illuminate\Database\Eloquent\Builder;

interface ProcessCmDemande
{
    public function process_demande(?int $id, ?array $data):bool;

    public function getView(): string;

    /**
     * Get the columns to update when processing the form.
     */
    public function formFields(?int $civility = null): array;

    public function field(): string;

    public function getFormView(): string;


    public function builder(): Builder;

}
