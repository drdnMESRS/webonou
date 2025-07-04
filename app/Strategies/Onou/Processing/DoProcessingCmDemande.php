<?php

namespace App\Strategies\Onou\Processing;

use App\Actions\Pages\Dossier_demande_Hebergement\UpdateDemandById;
use App\Actions\Sessions\RoleManagement;
use App\Models\Onou\Onou_cm_demande;
use App\Models\Onou\Onou_cm_etablissement;
use App\Models\Scopes\Dou\DouScope;
use App\Strategies\Onou\ProcessCmDemande;
use Illuminate\Database\Eloquent\Builder;

class DoProcessingCmDemande implements ProcessCmDemande
{
    public function process_demande(?int $id, ?array $data):bool
    {
        if (is_null($id) || is_null($data)) {
            return false; // Invalid parameters
        }

        $data = array_merge($data, [
            'dou'=> app(RoleManagement::class)->get_active_role_etablissement(),
            'approuvee_heb_dou' => true,
            'date_approuve_heb_dou'=> now(),
        ]);

        // dd($id, $data);
        // Fetch the demand by ID
        $demand = (new UpdateDemandById())->handle($id, $data);

        return true; // Successfully processed
    }

    public function getView(): string
    {
        return 'pages.processing-cm-demande.do-process-cm-demande';
    }

    /**
     * Get the columns to update when processing the form.
     */
    public function formFields(?int $civility = null): array
    {
        if (is_null($civility)) {
            // if civility is null, we can return all options
            $options = Onou_cm_etablissement::query()
                ->select('onou_cm_etablissement.*')
                ->with(['etablissement', 'type_nc'])
                ->open()// R1
                ->remember(60 * 60 * 24)
                ->get();
        } else {
            // if civility is not null and civility is mal (1), we can return only R1
            $options = ($civility === 1) ?
                Onou_cm_etablissement::query()
                    ->select('onou_cm_etablissement.*')
                    ->with(['etablissement', 'type_nc'])
                    ->garcon()
                    ->open()// R1
                    ->remember(60 * 60 * 24)
                    ->pluck('denomination_ar', 'id') : // if civility is not null and civility is femal (2), we can return only R1
                Onou_cm_etablissement::query()
                    ->select('onou_cm_etablissement.*')
                    ->with(['etablissement', 'type_nc'])
                    ->fille()
                    ->open()// R1
                    ->remember(60 * 60 * 24)
                    ->pluck('denomination_ar', 'id');
        }

        return [
            'residence' => [
                'type' => 'select',
                'label' => 'Residence',
                'name' => 'residence',
                'options' => $options,
            ],
        ];
    }

    public function field(): string
    {
        // TODO: Implement field() method.
        return 'residence';
    }

    public function getFormView(): string
    {
        return 'livewire.onou.forms.do';
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
}
