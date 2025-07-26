<?php

namespace App\Strategies\Onou\Processing;

use App\Actions\Pages\Dossier_demande_Hebergement\CreateAffectationIndividu;
use App\Actions\Pages\Dossier_demande_Hebergement\UpdateDemandById;
use App\Actions\Sessions\RoleManagement;
use App\Models\Nc\Nomenclature;
use App\Models\Onou\Onou_cm_demande;
use App\Models\Onou\Onou_cm_lieu;
use App\Strategies\Onou\ProcessCmDemande;

class RuProcessingCmDemande implements ProcessCmDemande
{
    public function process_demande(?int $id, ?array $data, ?string $action = 'accept'): bool
    {

        if (is_null($id) || is_null($data) || ! is_array($data) || ! in_array($action, ['accept', 'reject'])) {
            throw new \InvalidArgumentException('Invalid parameters provided for processing the demand.');
        }

        // Here you would implement the logic to process the request for RU
        // if action is 'accept', you might want to update the status of the request

        $checkAgeResult = session('checks.checkAge');
        if ($checkAgeResult && ($checkAgeResult['status'] ?? '') === 'danger') {
            throw new \Exception($checkAgeResult['message']);
        }
        $checkAgeResult = session('checks.reinscription');
        if ($checkAgeResult && ($checkAgeResult['status'] ?? '') === 'danger') {
            throw new \Exception($checkAgeResult['message']);
        }
        $checkAgeResult = session('checks.checkcles_remis');
        if ($checkAgeResult && ($checkAgeResult['status'] ?? '') === 'danger') {
            throw new \Exception($checkAgeResult['message']);
        }

        return ($action === 'accept') ? $this->acceptedProcess($id, $data)
            : $this->rejectProcess($id, $data);
    }

    public function process_clesremis(?int $id, ?array $data): bool
    {
        if (is_null($id) || is_null($data) || ! is_array($data)) {
            throw new \InvalidArgumentException('Invalid parameters provided for processing the demand.');
        }

        // TODO check if the student does not pay the fees throw an exception

        $data['cles_remis_at'] = now();
        (new UpdateDemandById)->handle($id, $data);

        return true;
    }

    public function getView(): string
    {
        return 'pages.processing-cm-demande.ru-process-cm-demande';
    }

    public function getViewClesRemis(): string
    {
        return 'pages.processing-cm-demande.ru-cles-remis-cm-demade';
    }

    /**
     * Get the columns to update when processing the form.
     */
    public function formFields(?int $civility = null, ?string $action = 'accept'): array
    {

        if ($action === 'reject') {
            return [
                'observ_heb_resid' => [
                    'type' => 'select',
                    'label' => 'Motif de refus',
                    'name' => 'observ_heb_resid',
                    'required' => true,
                    'options' => cache()->remember('reject_observ_heb_resid', 60 * 60 * 24, function () {
                        return Nomenclature::byListId(533)
                            ->pluck('libelle_long_ar', 'id')
                            ->prepend('Sélectionner un motif de refus', '');
                    }),
                ],
            ];
        }

        return [
            'field_update' => [
                'type' => 'hidden',
                'label' => 'Test Field',
                'placeholder' => 'Enter test value',
                'required' => true,
                'name' => 'field_update',
            ],
        ];
    }

    public function field(?string $action = 'accept'): string
    {
        // TODO: Implement field() method.
        return ($action === 'accept') ? 'affectation' : 'observ_heb_resid';
    }

    public function getFormView(): array
    {
        return
            [
                'accept' => 'livewire.onou.forms.ru',
                'reject' => 'livewire.onou.forms.ru',
            ];
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        // etablissement app(RoleManagement::class)->get_active_role_etablissement();
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
            )->leftJoin('cursus.conge_academique as cong', function ($q) {
                $q->on('cong.id_dossier_inscription', '=', 'dossier_inscription_administrative.id')
                    ->where('cong.demande_validee', true);
            })
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

    public function rules(?string $action = 'accept'): array
    {
        return [
            'field_update' => 'required|integer',
        ];
    }

    private function rejectProcess(?int $id, ?array $data): bool
    {
        $demande = Onou_cm_demande::find($id);
        if (! $demande) {
            throw new \Exception('Demande not found for the given  ');
        }
        // Here you would implement the logic to reject the request for RU
        $data = array_merge(
            $data,
            [
                'approuvee_heb_resid' => false,
                'date_approuve_heb_resid' => now(),
                'affectation' => null,
            ]
        );
        (new UpdateDemandById)->handle($id, $data);

        return true; // Implement rejection logic here
    }

    private function acceptedProcess(?int $id, ?array $data): bool
    {
        // create the affectation_individu
        $demande = Onou_cm_demande::find($id);
        if (! $demande) {
            throw new \Exception('Demande not found for the given  ');
        }
        $affectation = (new CreateAffectationIndividu)->handle(
            $demande->individu,
            $data['affectation']
        );
        // Check if the place is already occupied in the selected location

        $place = $demande->placeOccupe($data['affectation'])->first();
        if ($place && $place->place_occupe >= Onou_cm_lieu::find($data['affectation'])->capacite_reelle) {
            throw new \Exception('The selected location is already fully occupied. Please choose another location.');
        }
        // update the cm_demande with the new affectation ID
        $data = array_merge(
            $data,
            [
                'approuvee_heb_resid' => true,
                'date_approuve_heb_resid' => now(),
                'affectation' => $affectation,
            ]
        );
        (new UpdateDemandById)->handle($id, $data);

        return true; // Implement acceptance logic here
    }
}
