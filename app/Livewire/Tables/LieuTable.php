<?php

namespace App\Livewire\Tables;

use App\Livewire\Tables\Traits\LieuTable\LieuTrait;
use App\Models\Onou\Onou_cm_etablissement;
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
    'chambre'  => 699077,
    'unite'    => 699305,
];
    public function builder(): Builder
    {
        return Onou_cm_lieu::query()
                ->select(
                    'onou_cm_lieu.*'
                )
                ->with(['typeLieu', 'etablissementLieu', 'sousTypeLieu', 'parent'])
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
            Column::make('id', 'id')
                ->sortable()
                ->searchable(),
            Column::make('Nom', 'libelle_fr')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->full_name;
                    }
                )->sortable()->searchable(),
            Column::make('Type', 'type_lieu')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->typeLieu->full_name;
                    })
                ->sortable()
                ->searchable(),
            Column::make('Sous Type', 'sous_type_lieu')
                ->format(
                    function ($value, $row, Column $column) {
                        if (is_null($row->sousTypeLieu)) {
                            return 'N/A';
                        }
                        return $row->sousTypeLieu->full_name;
                    }
                )->sortable()->searchable(),
            Column::make('Etablissement', 'etablissement')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->etablissementLieu ? $row->etablissementLieu->full_name : 'N/A';
                    }
                )->sortable()->searchable(),

            Column::make('Parent', 'libelle_fr')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->parent ? $row->parent->full_name : 'N/A';
                    }
                )->sortable()->searchable(),
                Column::make('Capcite theorique', 'capacite_theorique')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->capacite_theorique ;
                    }
                )->sortable()->searchable(),
                Column::make('Capcite reelle', 'capacite_reelle')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->capacite_reelle ;
                    }
                )->sortable()->searchable(),
        ];
    }
}
