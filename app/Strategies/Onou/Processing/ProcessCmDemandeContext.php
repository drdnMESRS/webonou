<?php

namespace App\Strategies\Onou\Processing;

use App\Actions\Sessions\RoleManagement;
use App\Strategies\Onou\ProcessCmDemande;
use Illuminate\Database\Eloquent\Builder;

class ProcessCmDemandeContext implements ProcessCmDemande
{
    private ProcessCmDemande $cm_demande;

    public function __construct()
    {
        // TODO: Implement __construct() method. Create Object strategy based on auth user type.
        $type = app(RoleManagement::class)->get_active_type_etablissement();

        $this->cm_demande = match ($type) {
            'DO' => new DoProcessingCmDemande,
            'RU' => new RuProcessingCmDemande
        };
    }

    public function process_demande(?int $id, ?array $data): bool
    {
        return $this->cm_demande->process_demande($id, $data);
    }

    public function getView(): string
    {
        return $this->cm_demande->getView();
    }

    /**
     * Get the columns to update when processing the form.
     */
    public function formFields(?int $civility = null): array
    {
        if (is_null($civility)) {
            return $this->cm_demande->formFields();
        }

        return $this->cm_demande->formFields($civility);
    }

    public function field(): string
    {
        // TODO: Implement field() method.
        return $this->cm_demande->field();
    }

    public function getFormView(): string
    {
        // TODO: Implement getFormView() method.
        return $this->cm_demande->getFormView();
    }

    public function builder(): Builder
    {
        return $this->cm_demande->builder();
    }
}
