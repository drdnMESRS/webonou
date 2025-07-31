<?php

namespace App\Strategies\Onou\Processing;

use App\Actions\Pages\Dossier_demande_Hebergement\CreateDemand;
use App\Actions\Pages\Dossier_demande_Hebergement\UpdateDemandById;
use App\Actions\Sessions\RoleManagement;
use App\Models\Nc\Nomenclature;
use App\Models\Onou\Onou_cm_demande;
use App\Models\Onou\Onou_cm_etablissement;
use App\Models\Scopes\Dou\DouScope;
use App\Strategies\Onou\ProcessCmDemande;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class DoProcessingCmDemande implements ProcessCmDemande
{
    /**
     * Process the demand based on the provided ID, data, and action.
     *
     * @param  int|null  $id  The ID of the demand to process.
     * @param  array|null  $data  The data to update the demand with.
     * @param  string  $action  The action to perform ('accept' or 'reject').
     * @return bool True if the demand was successfully processed, false otherwise.
     */
    public function process_demande(?int $id, ?array $data, ?string $action = 'accept'): bool
    {

        if (is_null($data) || ! is_array($data) || ! in_array($action, ['accept', 'reject', 'create'])) {
            throw new \Exception('Invalid parameters provided for processing the demand.');
        }

        if (is_null($id) && in_array($action, ['accept', 'reject'])) {
            throw new \Exception('Invalid parameters provided for processing the demand.');
        }

        if(in_array($action, ['accept', 'create']) && isset($data['id_individu'])) {

            if (isset($data['id_dia'])) {

                $checkAgeResult = session('checks.checkAge');
                if ($checkAgeResult && ($checkAgeResult['status'] ?? '') === 'danger') {
                    throw new \Exception($checkAgeResult['message']);
                }
                $checkAgeResult = session('checks.reinscription');
                if ($checkAgeResult && ($checkAgeResult['status'] ?? '') === 'danger') {
                    throw new \Exception($checkAgeResult['message']);
                }
                $checkAgeResult = session('checks');
                dd( $checkAgeResult);
                if ($checkAgeResult && ($checkAgeResult['status'] ?? '') === 'danger') {
                    throw new \Exception($checkAgeResult['message']);
                }

                $checkAgeResult = session('checks.checkcles_remis');
                if ($checkAgeResult && ($checkAgeResult['status'] ?? '') === 'danger') {
                    throw new \Exception($checkAgeResult['message']);
                }
            }
            if (isset($data['id_fnd'])) {
                $checkAgeResult = session('checks.checkreinscription_doctort');
                if ($checkAgeResult && ($checkAgeResult['status'] ?? '') === 'danger') {
                    throw new \Exception($checkAgeResult['message']);
                }
            }
        }

        $data = array_merge($data, [
            'dou' => app(RoleManagement::class)->get_active_role_etablissement(),
            'traiter_par_dou' => app(RoleManagement::class)->get_active_id(),
            'approuvee_heb_dou' => in_array($action, ['accept', 'create']),
            'date_approuve_heb_dou' => now(),
            'affectation' => null,
        ]);

        if ($action === 'reject') {
            $data['approuvee_heb_dou'] = false;
            $data['residence'] = null; // Clear residence if rejecting
        } else {
            $data['observ_heb_dou'] = ''; // Clear observation if accepting
        }
        // Update the demand with the provided data

        $demand = [];
        if ($action === 'create') {
            $data = array_merge($data, [
                'annee_academique' => (new \App\Actions\Sessions\AcademicYearSession)->get_academic_year(),
                'id_dia' => $data['id_dia'],
                'individu' => $data['id_individu'],
            ]);

            $demand = (new CreateDemand)->handle($data);

            return true;
        } else {
            $demand = (new UpdateDemandById)->handle($id, $data);
            if (isset($demand['affectation'])) {
            }
        }

        return $demand->wasChanged(); // Successfully processed
    }

    public function process_clesremis(?int $id, ?array $data): bool
    {

        throw new \InvalidArgumentException('Vous n\'avez pas le droit.');
    }

    /**
     * Get the view for processing the demand.
     *
     * @return string The view name.
     */
    public function getView(): string
    {
        return 'pages.processing-cm-demande.do-process-cm-demande';
    }

    public function getViewClesRemis(): string
    {
        return 'pages.processing-cm-demande.dou-cles-remis-cm-demade';
    }

    /**
     * Get the form fields for processing the demand.
     *
     * @param  int|null  $civility  The civility of the individual
     * @param  string|null  $action  The action to perform ('accept' or 'reject').
     * @return array The form fields to be displayed.
     */
    public function formFields(?int $civility = null, ?string $action = 'accept'): array
    {
        $options = cache()->remember('residences_' . auth()->id() . '_' . $civility, 60 * 60 * 24, function () use ($civility) {
            // if civility is not null, we can return only R1
            return $this->getResidences($civility)
                ->pluck('denomination_ar', 'id')
                ->prepend(__('views/livewire/onou/forms/demande_hebergement/traitement_form.select_residence'), '');
        });

        $reject_fields = [
            'observ_heb_dou' => [
                'type' => 'select',
                'label' => __('views/livewire/onou/forms/demande_hebergement/traitement_form.motif'),
                'name' => 'observ_heb_dou',
                'required' => true,
                'options' => cache()->remember('reject_observ_heb_dou', 60 * 60 * 24, function () {
                    return Nomenclature::byListId(533)
                        ->pluck('libelle_long_ar', 'id')
                        ->prepend(__('views/livewire/onou/forms/demande_hebergement/traitement_form.select_motif'), '');
                }),
            ],
        ];

        $accept_fields = [
            'residence' => [
                'type' => 'select',
                'label' => __('views/livewire/onou/forms/demande_hebergement/traitement_form.residence'),
                'name' => 'residence',
                'required' => true,
                'options' => $options,
            ],
        ];

        if ($action === 'reject') {
            return $reject_fields;
        }

        return $accept_fields;
    }

    /**
     * Get the field name based on the action.
     *
     * @param  string  $action  The action to perform ('accept' or 'reject').
     * @return string The field name to be used in the form.
     */
    public function field(?string $action = 'accept'): string
    {
        // TODO: Implement field() method.
        return ($action === 'reject') ? 'observ_heb_dou' : 'residence';
    }

    /**
     * Get the form view for processing the demand.
     *
     * @return array The form view configuration.
     */
    public function getFormView(): array
    {
        return
            [
                'accept' => 'livewire.onou.forms.do',
                'reject' => 'livewire.onou.forms.do',
            ];
    }

    /**
     * Get the query builder for fetching the demands.
     *
     * @return Builder The query builder instance.
     */
    public function builder(): Builder
    {
        return $this->Graduation();
    }

    public function Graduation(): Builder
    {
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

            ->leftJoin('onou.onou_heb_affectation_etablissement as aff', function ($q) {
                $q->on('aff.etablissement_affectee', '=', 'dossier_inscription_administrative.id_etablissement');
            })

            ->leftJoin('cursus.conge_academique as cong', function ($q) {
                $q->on('cong.id_dossier_inscription', '=', 'dossier_inscription_administrative.id')
                    ->where('cong.demande_validee', true);
            })

            ->select(
                'onou.onou_cm_demande.*',
                'individu_detais.identifiant as individu_identifiant',
                'individu_detais.nom_latin as individu_nom_latin',
                'individu_detais.civilite as individu_civilite',
                'dossier_inscription_administrative.numero_inscription as dia_numero_inscription',
                'dossier_inscription_administrative.*',
                'cong.demande_validee',
            )
            ->where(function ($q) {
                $q->where('onou_cm_demande.dou', '=', app(RoleManagement::class)->get_active_role_etablissement())
                    ->orWhereNull('onou_cm_demande.dou');
            })
            ->where('aff.dou', '=', app(RoleManagement::class)->get_active_role_etablissement())
            ->withoutGlobalScope(DouScope::class)

            ->remember(60);
    }

    public function PostGraduation(): Builder
    {
        return Onou_cm_demande::query()
            ->with([
                'individu_detais',
                'fichier_national_doctorant',
                'fichier_national_doctorant.domaine',
                'fichier_national_doctorant.filiere',
                'fichier_national_doctorant.etablissement:id,ll_etablissement_arabe,ll_etablissement_latin',
                'suiv_fichier_national_doctorant',
                'nc_commune_residence',
            ])
            ->leftJoin(
                'ppm.ref_individu AS individu_detais',
                'onou.onou_cm_demande.individu',
                '=',
                'individu_detais.id'
            )
            ->leftJoin(
                'doctorat.fichier_national_doctorant AS fichier_national_doctorant',
                'onou.onou_cm_demande.id_fnd',
                '=',
                'fichier_national_doctorant.id'
            )
            ->leftjoin(
                'doctorat.suivi_fichier_national_doctorant AS suiv_fichier_national_doctorant',
                function ($join) {
                    $join->on('fichier_national_doctorant.id', '=', 'suiv_fichier_national_doctorant.id_fnd')
                        ->where('suiv_fichier_national_doctorant.id_annee_academique', '=', (new \App\Actions\Sessions\AcademicYearSession)->get_academic_year());
                }
            )
            ->leftJoin('onou.onou_heb_affectation_etablissement as aff', function ($q) {
                $q->on('aff.etablissement_affectee', '=', 'fichier_national_doctorant.id_etablissement');
            })
            ->select(
                'onou.onou_cm_demande.*',
                'individu_detais.identifiant as individu_identifiant',
                'individu_detais.nom_latin as individu_nom_latin',
                'individu_detais.civilite as individu_civilite',
                'suiv_fichier_national_doctorant.numero_inscription as fnd_numero_inscription',
                'fichier_national_doctorant.*',
                'suiv_fichier_national_doctorant.*',
            )
            ->where(function ($q) {
                $q->where('onou_cm_demande.dou', '=', app(RoleManagement::class)->get_active_role_etablissement())
                    ->orWhereNull('onou_cm_demande.dou');
            })
            ->where('aff.dou', '=', app(RoleManagement::class)->get_active_role_etablissement())
            ->withoutGlobalScope(DouScope::class)
            ->remember(60);
    }

    /**
     * @return mixed
     */
    private function getResidences(?int $civility): \Illuminate\Database\Eloquent\Builder
    {
        if (is_null($civility)) {
            // if civility is null, we can return all options
            $options = Onou_cm_etablissement::query()
                ->select('onou_cm_etablissement.*')
                ->with(['etablissement', 'type_nc'])
                ->open() // R1
                ->remember(60 * 60 * 24);
        } else {
            // if civility is not null and civility is mal (1), we can return only R1
            $options = ($civility === 1) ?
                Onou_cm_etablissement::query()
                ->select('onou_cm_etablissement.*')
                ->with(['etablissement', 'type_nc'])
                ->garcon()
                ->open() // R1
                ->remember(60 * 60 * 24) : // if civility is not null and civility is femal (2), we can return only R1
                Onou_cm_etablissement::query()
                ->select('onou_cm_etablissement.*')
                ->with(['etablissement', 'type_nc'])
                ->fille()
                ->open() // R1
                ->remember(60 * 60 * 24);
        }

        return $options;
    }

    /**
     * Get the validation rules for processing the demand.
     *
     * @param  string|null  $action  The action to perform ('accept' or 'reject').
     * @return array The validation rules.
     */
    public function rules(?string $action = 'accept'): array
    {
        // Get the list of residences and the nomenclature IDs for rejection
        $nc = Nomenclature::byListId(533)->pluck('id');
        $residences = $this->getResidences(null)->pluck('id');
        if ($action === 'reject') {
            return [
                'field_update' => [
                    'required',
                    Rule::in($nc),
                ],
            ];
        }
        if ($action === 'create') {
            return [
                'field_update' => [
                    'required',
                    'integer',
                    Rule::in($residences),
                ],
                'data.id_individu' => [
                    'required',
                    'integer',
                ],
                'data.id_dia' => [
                    'integer',
                    'nullable',
                    'required_without:data.id_fnd',
                ],
                'data.affectation' => [
                    'integer',
                    'nullable',
                ],
                'data.id_fnd' => [
                    'integer',
                    'nullable',
                    'required_without:data.id_dia',
                ],

            ];
        }

        return [
            'field_update' => [
                'required',
                'integer',
                Rule::in($residences),
            ],
            'data.id_dia' => [
                'integer',
                'nullable',
                'required_without:data.id_fnd',
            ],
            'data.id_fnd' => [
                'integer',
                'nullable',
                'required_without:data.id_dia',
            ],

        ];
    }
}
