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

    public int $typestudent = 1;

    public function __construct()
    {
        $this->processCmDemande = new ProcessCmDemandeContext;
    }

    public function builder(): Builder
    {
        if ($this->getAppliedFilterWithValue('typestudent') == 2) {
            return $this->processCmDemande->PostGraduation();
        }

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

            'typestudent' => SelectFilter::make(__('livewire/tables/onou_cm_demande_table.typestudent'))
                ->options([
                    1 => __('livewire/tables/onou_cm_demande_table.graduation'),
                    2 => __('livewire/tables/onou_cm_demande_table.post_graduation'),
                ])
                ->filter(function (Builder $builder, $value) {
                    return $builder;
                }),
            'civilite' => SelectFilter::make(__('livewire/tables/onou_cm_demande_table.sexe'))
                ->options([
                    1 => __('livewire/tables/onou_cm_demande_table.garcons'),
                    2 => __('livewire/tables/onou_cm_demande_table.filles'),
                ])
                ->filter(function (Builder $builder, $value) {
                    return $builder->where('civilite', $value);
                }),
            'Inscription' => SelectFilter::make(__('livewire/tables/onou_cm_demande_table.situation'))
                ->options([
                    '1' => __('livewire/tables/onou_cm_demande_table.tous'),
                    '2' => __('livewire/tables/onou_cm_demande_table.transfert'),
                    '3' => __('livewire/tables/onou_cm_demande_table.conge_academique'),
                ])
                ->filter(function (Builder $builder, $value) {

                    switch ($value) {

                        case '2':
                            return $builder->where('est_transfert', true);

                        case '3':
                            return $builder->where('cong.demande_validee', true);
                        default:

                            return $builder;
                    }
                }),

            'traiter' => BooleanFilter::make(__('livewire/tables/onou_cm_demande_table.traitee'))
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
            ->setLoadingPlaceholderContent(
                '<div class="flex items-center justify-center h-64">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-3 border-gray-900"></div>
                </div>'
            );

        $this->setConfigurableArea('after-tools', 'partials.filters.domain-filter');
        $this->setConfigurableArea('toolbar-right-end', 'partials.table-components.export');
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
        if (is_null($row->approuvee_heb_dou)) {
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
            Column::make(__('livewire/tables/onou_cm_demande_table.nin'), 'individu_detais.identifiant')
                ->searchable(),
            Column::make(__('livewire/tables/onou_cm_demande_table.numero_inscription'), 'dossier_inscription_administrative.numero_inscription')
                ->searchable()
                ->format(
                    fn ($value, $row) => $row->suiv_fichier_national_doctorant->numero_inscription ?? $row->dossier_inscription_administrative->numero_inscription ?? '-'
                )->searchable(),

            Column::make(__('livewire/tables/onou_cm_demande_table.individu'), 'individu_detais.nom_latin')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->individu_detais->full_name ?? '';
                    }
                ),
            Column::make(__('livewire/tables/onou_cm_demande_table.sexe'), 'individu_detais.civilite')
                ->format(
                    function ($value, $row, Column $column) {
                        if (app()->getLocale() === 'ar') {
                            return $row->individu_detais->civilite === 1 ? 'ذ' : 'أ';
                        } else {
                            return $row->individu_detais->civilite === 1 ? 'G' : 'F';
                        }
                    }
                ),
            Column::make(__('livewire/tables/onou_cm_demande_table.residence'), 'residenceaffectation.id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->residenceaffectation->full_name ?? '';
                    }
                ),
            Column::make(__('livewire/tables/onou_cm_demande_table.pavillon'), 'affectationlieu.id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->affectationlieu->lieuaffectation->parent->full_name ?? '';
                    }
                ),
            Column::make(__('livewire/tables/onou_cm_demande_table.chambre'), 'affectationlieu.id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->affectationlieu->lieuaffectation->full_name ?? '';
                    }
                ),
            Column::make(__('livewire/tables/onou_cm_demande_table.comune_residence'), 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->nc_commune_residence->full_name ?? '';
                    }
                ),
            Column::make(__('livewire/tables/onou_cm_demande_table.etablissement'), 'dossier_inscription_administrative.id_etablissement')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->dossier_inscription_administrative->etablissement->full_name ?? '';
                    }
                ),
            Column::make(__('livewire/tables/onou_cm_demande_table.domaine'), 'dossier_inscription_administrative.id_domaine')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->dossier_inscription_administrative->domaine->full_name ?? '';
                    }
                ),
            Column::make(__('livewire/tables/onou_cm_demande_table.filiere'), 'dossier_inscription_administrative.id_filiere')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->dossier_inscription_administrative->filiere->full_name ?? '';
                    }
                ),
            Column::make(__('livewire/tables/onou_cm_demande_table.niveau'), 'dossier_inscription_administrative.id_niveau')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->dossier_inscription_administrative->niveau->full_name ?? '';
                    }
                ),
            BooleanColumn::make(__('livewire/tables/onou_cm_demande_table.payment_inscription'), 'dossier_inscription_administrative.frais_inscription_paye'),
            BooleanColumn::make(__('livewire/tables/onou_cm_demande_table.payment_hebergement'), 'hebergement_paye'),
            BooleanColumn::make(__('livewire/tables/onou_cm_demande_table.cle_remise'), 'cles_remis'),

        ];
    }
}
