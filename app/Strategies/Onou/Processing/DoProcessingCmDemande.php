<?php

namespace App\Strategies\Onou\Processing;

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
    public function process_demande(?int $id, ?array $data, ?string $action='accept'):bool
    {
        if (is_null($id) || is_null($data) || !is_array($data) || !in_array($action, ['accept', 'reject'])) {
           throw new \InvalidArgumentException('Invalid parameters provided for processing the demand.');
        }

        $data = array_merge($data, [
            'dou'=> app(RoleManagement::class)->get_active_role_etablissement(),
            'approuvee_heb_dou' => $action==='accept',
            'date_approuve_heb_dou'=> now(),
        ]);
        if ($action === 'reject') {
            $data['residence'] = null; // Clear residence if rejecting
        }else {
            $data['observ_heb_dou'] = ''; // Clear observation if accepting
        }
        // Update the demand with the provided data
        $demand = (new UpdateDemandById())->handle($id, $data);

        return $demand->wasChanged(); // Successfully processed
    }

    public function getView(): string
    {
        return 'pages.processing-cm-demande.do-process-cm-demande';
    }

    /**
     * Get the columns to update when processing the form.
     */
    public function formFields( ?int $civility = null, ?string $action='accept'): array
    {
        $options = cache()->remember('residences_' .auth()->id().'_'.$civility, 60 * 60 * 24, function () use ($civility) {
            // if civility is not null, we can return only R1
            return $this->getResidences($civility)
                ->pluck('denomination_ar', 'id')
                ->prepend('Sélectionner une résidence', '');
        });

        $reject_fields = [
            'observ_heb_dou' => [
                'type' => 'select',
                'label' => 'Motif de refus',
                'name' => 'observ_heb_dou',
                'required' => true,
                'options' => cache()->remember('reject_observ_heb_dou', 60 * 60 * 24, function () {
                   return Nomenclature::byListId(533)
                    ->pluck('libelle_long_ar', 'id')
                    ->prepend('Sélectionner un motif de refus', '');
                }),
            ],
        ];

        $accept_fields = [
            'residence' => [
                'type' => 'select',
                'label' => 'Residence',
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

    public function field(?string $action='accept'): string
    {
        // TODO: Implement field() method.
        return ($action==='accept') ? 'residence': 'observ_heb_dou';
    }

    public function getFormView(): array
    {
        return
            [
                'accept'=>'livewire.onou.forms.do',
                'reject'=>'livewire.onou.forms.do'
            ];
    }

    public function builder(): Builder
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

           ->select(
               'onou.onou_cm_demande.*',
               'individu_detais.identifiant as individu_identifiant',
               'individu_detais.nom_latin as individu_nom_latin',
               'individu_detais.civilite as individu_civilite',
               'dossier_inscription_administrative.numero_inscription as dossier_inscription_numero',
               'dossier_inscription_administrative.*',
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
     * @param int|null $civility
     * @return mixed
     */
    private function getResidences(?int $civility): \Illuminate\Database\Eloquent\Builder
    {
        if (is_null($civility)) {
            // if civility is null, we can return all options
            $options = Onou_cm_etablissement::query()
                ->select('onou_cm_etablissement.*')
                ->with(['etablissement', 'type_nc'])
                ->open()// R1
                ->remember(60 * 60 * 24);
        } else {
            // if civility is not null and civility is mal (1), we can return only R1
            $options = ($civility === 1) ?
                Onou_cm_etablissement::query()
                    ->select('onou_cm_etablissement.*')
                    ->with(['etablissement', 'type_nc'])
                    ->garcon()
                    ->open()// R1
                    ->remember(60 * 60 * 24): // if civility is not null and civility is femal (2), we can return only R1
                Onou_cm_etablissement::query()
                    ->select('onou_cm_etablissement.*')
                    ->with(['etablissement', 'type_nc'])
                    ->fille()
                    ->open()// R1
                    ->remember(60 * 60 * 24);
        }
        return $options;
    }

    public function rules(?string $action='accept'): array
    {
        $nc = Nomenclature::byListId(533)->pluck('id');
        $residences = $this->getResidences(null)->pluck('id');
        if ($action === 'reject') {
                    return [
                        'field_update' => [
                            'required',
                            Rule::in($nc),
                            ]
                    ];
                }
        return [
                'field_update' => [
                    'required',
                    'integer',
                    Rule::in($residences),
                ],
            ];
    }
}
