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

    public function builder(): Builder
    {
        return Onou_cm_lieu::query()
                ->select(
                    'onou_cm_lieu.*'
                )
                ->with(['typeLieu', 'etablissementLieu', 'sousTypeLieu', 'parent'])
                ->remember(60 * 60 * 3); // Cache for 24 hours
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
            Column::make('id', 'id')
                ->sortable()
                ->searchable(),
            Column::make('Nom', 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->full_name;
                    }
                )->sortable()->searchable(),
            Column::make('Type', 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->typeLieu->full_name;
                    })
                ->sortable()
                ->searchable(),
            Column::make('Sous Type', 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        if (is_null($row->sousTypeLieu)) {
                            return 'N/A';
                        }
                        return $row->sousTypeLieu->full_name;
                    }
                )->sortable()->searchable(),
            Column::make('Etablissement', 'etablissementLieu.id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->etablissementLieu ? $row->etablissementLieu->full_name : 'N/A';
                    }
                )->sortable()->searchable(),

            Column::make('Parent', 'id')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->parent ? $row->parent->full_name : 'N/A';
                    }
                )->sortable()->searchable(),
        ];
    }
}
