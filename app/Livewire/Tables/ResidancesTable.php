<?php

namespace App\Livewire\Tables;

use App\Livewire\Onou\ResidanceDetails;
use App\Models\Onou\Onou_cm_etablissement;
use App\Models\Ppm\Ref_structure;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;


/**
 * Class ResidancesTable
 *
 * @package App\Livewire\Tables
 */


class ResidancesTable extends DataTableComponent
{
    //protected $model = Onou_cm_etablissement::class;


    public function builder(): Builder
    {
        return Onou_cm_etablissement::query()
            ->select('onou_cm_etablissement.*')
            ->with(['etablissement', 'type_nc']);
    }
    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setTrAttributes(
            function ($row) {
                $this->dispatch('residence-show')->to(ResidanceDetails::class);
                return [
                    '@click'=>"\$dispatch('residence-show', {id: ' $row->id '})",
                    'style' => 'cursor: pointer; background-color: #f9f9f9;'
                ];
            });
    }

    public function columns(): array
    {
        return [
            Column::make("Code", "etablissement.identifiant")
                ->sortable()
                ->searchable(),
            Column::make("ll_structure_latin", "id")
                ->format(
               function ($value, $row, Column $column) {
                  return $row->full_name;
               }
            )->sortable(),
            Column::make("Type", "id")
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->type_nc->full_name;
                    })
                ->sortable()
                ->searchable(),
            Column::make("capacite_theorique", "capacite_theorique"),
            Column::make("capacite_reelle", "capacite_relle"),
        ];
    }
}
