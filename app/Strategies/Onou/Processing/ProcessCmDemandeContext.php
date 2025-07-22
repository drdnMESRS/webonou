<?php

namespace App\Strategies\Onou\Processing;

use App\Actions\Sessions\RoleManagement;
use App\Models\Lmd\Filiere_lmd;
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

    public function process_demande(?int $id, ?array $data, ?string $action = 'accept'): bool
    {
        return $this->cm_demande->process_demande($id, $data, $action);
    }
    public function process_clesremis(?int $id, ?array $data): bool
    {
        return  $this->cm_demande->process_clesremis($id, $data);
    }
    public function getView(): string
    {
        return $this->cm_demande->getView();
    }
 public function getViewClesRemis(): string
     {
   return $this->cm_demande->getViewClesRemis();
     }
    /**
     * Get the columns to update when processing the form.
     */
    public function formFields(?int $civility = null, ?string $action = 'accept'): array
    {
        if (is_null($civility)) {
            return $this->cm_demande->formFields(null, $action);
        }

        return $this->cm_demande->formFields($civility, $action);
    }

    public function field(?string $action = 'accept'): string
    {
        // TODO: Implement field() method.
        return $this->cm_demande->field($action);
    }

    public function getFormView(): array
    {
        // TODO: Implement getFormView() method.
        return $this->cm_demande->getFormView();
    }

    public function builder(): Builder
    {
        return $this->cm_demande->builder();
    }

    public function rules(?string $action = 'accept'): array
    {
        return $this->cm_demande->rules($action);
    }

    public function getFiliereFilterOptions(): array
    {
        return cache()->remember('filter_options', 60 * 60 * 24, function () {
            return Filiere_lmd::query()
                ->pluck('ll_filiere', 'id')
                ->prepend('Sélectionner un domaine', '')
                ->toArray();
        });
    }
}
