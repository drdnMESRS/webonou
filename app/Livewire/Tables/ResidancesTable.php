<?php

namespace App\Livewire\Tables;

use App\Models\Onou\Onou_cm_etablissement;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class ResidancesTable
 */
class ResidancesTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Onou_cm_etablissement::query()
            ->select('onou_cm_etablissement.*')
            ->with(['etablissement', 'type_nc'])
            ->remember(60 * 60 * 24); // Cache for 24 hours
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setTrAttributes(
                function ($row) {
                    return [
                        '@click' => "\$dispatch('residence-show', {id: ' $row->id '})",
                        'style' => 'cursor: pointer; background-color: #f9f9f9;',
                    ];
                });
    }

    public function columns(): array
    {
        return [
            Column::make(__('livewire/tables/residences_table.code'), 'etablissement.identifiant')
                ->sortable()
                ->searchable(),
            Column::make(__('livewire/tables/residences_table.nom'), 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->full_name;
                    }
                )->sortable()->searchable(),
            Column::make(__('livewire/tables/residences_table.type'), 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->type_nc->full_name;
                    })
                ->sortable()
                ->searchable(),
            Column::make(__('livewire/tables/residences_table.capacite_theorique'), 'capacite_theorique'),
            Column::make(__('livewire/tables/residences_table.capacite_reelle'), 'capacite_relle'),
        ];
    }
}
