<?php

namespace App\Livewire\Tables;

use App\Actions\Sessions\RoleManagement;
use App\Strategies\Onou\ProcessCmDemande;
use App\Strategies\Onou\Processing\ProcessCmDemandeContext;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\BooleanFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Class ResidancesTable
 */
class OnouCmDemandeTable extends DataTableComponent
{
    private ProcessCmDemande $processCmDemande;

    public function __construct()
    {
        $this->processCmDemande = new ProcessCmDemandeContext;
    }

    public function builder(): Builder
    {
        return $this->processCmDemande->builder();
    }

    public function filters(): array
    {
        return [
            'filiere' => SelectFilter::make('filiere')
                ->options($this->processCmDemande->getFiliereFilterOptions())
                ->filter(function (Builder $builder, $value) {
                    return $builder->where('dossier_inscription_administrative.id_filiere', $value);
                })->hiddenFromAll(),
            'civilite' => SelectFilter::make('Civilité')
                ->options([
                    1 => 'Garçon',
                    2 => 'Fille',
                ])
                ->filter(function (Builder $builder, $value) {
                    return $builder->where('civilite', $value);
                }),
            'traiter' => BooleanFilter::make('Traitée')
                ->setFilterPillValues([

                    true => 'Active',

                    false => 'Inactive',

                ])
                ->filter(function (Builder $builder, bool $processed) {
                    if ($processed) {
                        if (app(RoleManagement::class)->get_active_type_etablissement() === 'DO') {
                            return $builder->whereNotNull('approuvee_heb_dou');
                        }

                        return $builder->whereNotNull('approuvee_heb_resid');
                    }
                    if (app(RoleManagement::class)->get_active_type_etablissement() === 'DO') {
                        return $builder->whereNull('approuvee_heb_dou');
                    }

                    return $builder->whereNull('approuvee_heb_resid');
                }),
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setTrAttributes(fn ($row) => $this->getTrAttributesConfig($row))
            ->setTdAttributes(fn ($column, $row) => $this->getTdAttributesConfig($row))
            ->setLoadingPlaceholderEnabled()
            ->setLoadingPlaceholderContent('<div class="flex items-center justify-center h-64">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-3 border-gray-900"></div>
                </div>'
            );

        $this->setConfigurableArea('after-tools', 'partials.filters.domain-filter');
    }

    private function getTrAttributesConfig($row): array
    {
        return [
            '@click' => " \$dispatch('loader-show'); \$dispatch('demande-show', {id: ' $row->id '});",
            'style' => 'cursor: pointer;',
        ];
    }

    private function getTdAttributesConfig($row): array
    {
        if (app(RoleManagement::class)->get_active_type_etablissement() === 'DO') {
            return $this->getTdAttributesForDO($row);
        }

        return $this->getTdAttributesForResid($row);
    }

    private function getTdAttributesForDO($row): array
    {
        if (is_null($row->approuvee_heb_dou) || is_null($row->residence)) {
            return ['class' => 'bg-yellow-50'];
        }

        return $row->approuvee_heb_dou
            ? ['class' => 'bg-green-50']
            : ['class' => 'bg-red-50'];
    }

    private function getTdAttributesForResid($row): array
    {
        if (is_null($row->approuvee_heb_resid)) {
            return ['class' => 'bg-yellow-50'];
        }

        return $row->approuvee_heb_resid
            ? ['class' => 'bg-green-50']
            : ['class' => 'bg-red-50'];
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
                            return $row->individu_detais->civilite === 1 ? 'ذ' : 'أ';
                        } else {
                            return $row->individu_detais->civilite === 1 ? 'G' : 'F';
                        }
                    }
                ),
                Column::make('Residence', 'residenceaffectation.id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->residenceaffectation->denomination_fr?? '';
                    }
                ),
            Column::make('PAVILLON', 'affectationlieu.id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->affectationlieu->lieuaffectation->parent->libelle_fr ?? '';
                    }
                ),
            Column::make('Chambre', 'affectationlieu.id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->affectationlieu->lieuaffectation->libelle_fr ?? '';
                    }
                ),
            Column::make('Comune de résidance', 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->nc_commune_residence->full_name ?? '';
                    }
                ),
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
            BooleanColumn::make('frais_inscription_paye', 'dossier_inscription_administrative.frais_inscription_paye'),
            BooleanColumn::make('paiment', 'hebergement_paye'),

        ];
    }
}
