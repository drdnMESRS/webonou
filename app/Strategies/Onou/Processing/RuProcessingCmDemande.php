<?php

namespace App\Strategies\Onou\Processing;

use App\Actions\Sessions\RoleManagement;
use App\Models\Onou\Onou_cm_demande;
use App\Models\Scopes\Dou\DouScope;
use App\Strategies\Onou\ProcessCmDemande;

class RuProcessingCmDemande implements ProcessCmDemande
{
    public function process_demande(?int $id, ?array $data, ?string $action='accept'): bool
    {
       return true;
    }

    public function getView(): string
    {
        return 'pages.processing-cm-demande.ru-process-cm-demande';
    }

    /**
     * Get the columns to update when processing the form.
     */
    public function formFields(?int $civility = null, ?string $action='accept'): array
    {
        return [
            'field_update' => [
                'label' => 'Mettre à jour le champ',
                'type' => 'select',
                'options' => [
                    1 => 'Option 1',
                    2 => 'Option 2',
                    3 => 'Option 3',
                ],
                'required' => true,
            ],
        ];
    }

    public function field(?string $action='accept'): string
    {
        // TODO: Implement field() method.
        return 'affectation';
    }

    public function getFormView(): array
    {
        return
            [
                'accept'=>'livewire.onou.forms.ru',
                'reject'=>'livewire.onou.forms.ru-reject'
            ];
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        //etablissement app(RoleManagement::class)->get_active_role_etablissement();
        return Onou_cm_demande::query()
            ->with([
                'individu_detais',
                'dossier_inscription_administrative',
                'dossier_inscription_administrative.ouvertureOf',
                'dossier_inscription_administrative.niveau',
                'dossier_inscription_administrative.etablissement:id,ll_etablissement_arabe,ll_etablissement_latin',
                'dossier_inscription_administrative.domaine',
                'dossier_inscription_administrative.filiere',
                'nc_commune_residence',
            ])
            ->leftJoin(
                'ppm.ref_individu AS individu_detais',
                'onou.onou_cm_demande.individu',
                '=',
                'individu_detais.id'
            )
            ->leftJoin(
                'cursus.dossier_inscription_administrative AS dossier_inscription_administrative',
                'onou.onou_cm_demande.id_dia',
                '=',
                'dossier_inscription_administrative.id'
            )
            ->select(
                'onou.onou_cm_demande.*',
                'individu_detais.identifiant as individu_identifiant',
                'individu_detais.nom_latin as individu_nom_latin',
                'individu_detais.civilite as individu_civilite',
                'dossier_inscription_administrative.numero_inscription as dossier_inscription_numero',
                'dossier_inscription_administrative.*',
            )
            ->where(function ($q) {
                $q->where('onou_cm_demande.residence', '=', app(RoleManagement::class)->get_active_role_etablissement());
            })
            ->remember(60);
    }
    public function rules(?string $action='accept'): array
    {
        return [
            'field_update' => 'required|integer',
        ];
    }
}
