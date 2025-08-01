<?php

namespace App\Livewire\Tables;

use App\Livewire\Tables\Traits\LieuTable\LieuTrait;
use App\Models\Onou\Onou_cm_lieu;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class ResidancesTable
 */
class LieuTable extends DataTableComponent
{
    use LieuTrait;

    public array $specialTypes = [
        'pavilion' => 699076,
        'chambre' => 699077,
        'unite' => 699305,
    ];

    public function builder(): Builder
    {
        return Onou_cm_lieu::query()
            ->select(
                'onou_cm_lieu.*'
            )->withCount(['children', 'affectation'])
            ->with(['etatLieu', 'typeLieu', 'etablissementLieu', 'sousTypeLieu', 'parent'])
            ->whereIn('type_lieu', array_values($this->specialTypes))
            ->remember(60 * 60 * 3); // Cache for 24 hours
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setTrAttributes(
                function ($row) {
                    return [
                        '@click' => "\$dispatch('lieu-show', {id: ' $row->id '})",
                        'style' => 'cursor: pointer; background-color: #f9f9f9;',
                    ];
                });
    }

    public function columns(): array
    {
        return [
            Column::make(__('livewire/tables/lieu_table.id'), 'id')
                ->sortable()
                ->searchable(),
            Column::make(__('livewire/tables/lieu_table.nom'), 'libelle_fr')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->full_name;
                    }
                )->sortable()->searchable(),
            Column::make(__('livewire/tables/lieu_table.type'), 'type_lieu')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->typeLieu->full_name;
                    })
                ->sortable()
                ->searchable(),
            Column::make(__('livewire/tables/lieu_table.sous_type'), 'sous_type_lieu')
                ->format(
                    function ($value, $row, Column $column) {
                        if (is_null($row->sousTypeLieu)) {
                            return 'N/A';
                        }

                        return $row->sousTypeLieu->full_name;
                    }
                )->sortable()->searchable(),
            Column::make(__('livewire/tables/lieu_table.etablissement'), 'etablissement')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->etablissementLieu ? $row->etablissementLieu->full_name : 'N/A';
                    }
                )->sortable()->searchable(),

            Column::make(__('livewire/tables/lieu_table.parent'), 'libelle_fr')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->parent ? $row->parent->full_name : 'N/A';
                    }
                )->sortable()->searchable(),
            Column::make(__('livewire/tables/lieu_table.etat'), 'id')
                ->format(
                    function ($value, $row, Column $column) {

                        return $row->etatLieu ? $row->etatLieu->full_name : '';
                    })
                ->sortable()
                ->searchable(),
            Column::make(__('livewire/tables/lieu_table.sous_lieu'), 'id')
                ->format(
                    function ($value, $row, Column $column) {

                        return $row->typeLieu->id == $this->specialTypes['chambre'] ? '' : $row->children_count;
                    })
                ->sortable(),

            Column::make(__('livewire/tables/lieu_table.surface'), 'surface_globale')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->surface_globale;
                    }
                )->sortable(),
            Column::make(__('livewire/tables/lieu_table.capacite_theorique'), 'capacite_theorique')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->capacite_theorique;
                    }
                )->sortable()->searchable(),
            Column::make(__('livewire/tables/lieu_table.capacite_reelle'), 'capacite_reelle')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->capacite_reelle;
                    }
                )->sortable()->searchable(),
            Column::make(__('livewire/tables/lieu_table.affectes'), 'id')
                ->format(
                    function ($value, $row, Column $column) {

                        return $row->typeLieu->id == $this->specialTypes['chambre'] ? $row->affectation_count : '';
                    })
                ->sortable(),

        ];
    }
}
