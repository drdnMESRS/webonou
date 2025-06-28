<?php

namespace App\Livewire\Tables;

use App\Actions\Sessions\RoleManagement;
use App\Models\Onou\Onou_cm_demande;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class ResidancesTable
 */
class OnouCmDemandeTable extends DataTableComponent
{
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
            ->select(
                'onou.onou_cm_demande.*',
                'individu_detais.identifiant as individu_identifiant',
                'individu_detais.nom_latin as individu_nom_latin',
                'individu_detais.civilite as individu_civilite',
                'dossier_inscription_administrative.numero_inscription as dossier_inscription_numero',
                'dossier_inscription_administrative.*',
            )
            ->remember(60);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setTrAttributes(
                function ($row) {
                    return [
                        '@click' => "\$dispatch('loader-show'); \$dispatch('demande-show', {id: ' $row->id '})",
                        'style' => 'cursor: pointer;',

                    ];
                })
            ->setTdAttributes(
                function ($column, $row ) {
                    // test baed on type of role
                    if(app(RoleManagement::class)->get_active_type_etablissement() == 'DO') {
                        if ($row->approuvee_heb_dou)
                            return [
                                'class' => 'bg-green-50',
                            ];
                        if (!$row->approuvee_heb_dou)
                            return [
                                'class' => 'bg-red-50',
                            ];
                        return [];
                    }else {
                        if ($row->approuvee_heb_resid)
                            return [
                                'class' => 'bg-green-50',
                            ];
                        if (!$row->approuvee_heb_resid)
                            return [
                                'class' => 'bg-red-50',
                            ];
                        return [];
                    }
                }
            )->setPaginationMethod('simple');
    }

    public function columns(): array
    {
        return [
            Column::make('NIN', 'individu_detais.identifiant')
                ->searchable(),
            Column::make('Numero inscription', 'dossier_inscription_administrative.numero_inscription')
                ->searchable(),
            Column::make('Individu', 'individu_detais.nom_latin')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->individu_detais->full_name ?? '';
                    }
                ),
            Column::make('Sex', 'individu_detais.civilite')
                ->format(
                    function ($value, $row, Column $column) {
                        if (app()->getLocale() === 'ar') {
                            return $row->individu_detais->civilite === '1' ? 'ذ' : 'أ';
                        } else {
                            return $row->individu_detais->civilite === '1' ? 'G' : 'F';
                        }
                    }
                )
                ->sortable(),
            Column::make('Comune de résidance', 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->nc_commune_residence->full_name ?? '';
                    }
                )
                ->sortable()
                ->searchable(),
            Column::make('Etablissement', 'dossier_inscription_administrative.id_etablissement')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->dossier_inscription_administrative->etablissement->full_name ?? '';
                    }
                ),
            Column::make('Domaine', 'dossier_inscription_administrative.id_domaine')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->dossier_inscription_administrative->domaine->full_name ?? '';
                    }
                ),
            Column::make('Filière', 'dossier_inscription_administrative.id_filiere')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->dossier_inscription_administrative->filiere->full_name ?? '';
                    }
                ),
            Column::make('Niveau', 'dossier_inscription_administrative.id_niveau')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->dossier_inscription_administrative->niveau->full_name ?? '';
                    }
                ),

        ];
    }
}
