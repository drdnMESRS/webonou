<?php

namespace App\Livewire\Tables;

use App\Models\Onou\vm_heb_processing_by_ru;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class ResidancesTable
 */
class OnouCmStatTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return vm_heb_processing_by_ru::query()->with(['etablissement'])
          //  ->select('vm_heb_processing_by_ru.*');
            ->whereNotNull('residence');
        // /->join('onou.onou_cm_demande','onou.onou_cm_demande.dou','vm_heb_processing_by_ru.dou')
        //   ->join('onou.onou_cm_etablissement','vm_heb_processing_by_ru.residence','onou.onou_cm_etablissement.id');
        //  ->remember(60 * 60 * 24); // Cache for 24 hours
    }

    public function configure(): void
    {
        $this->setPrimaryKey('residence')
            ->setTdAttributes(fn ($column, $row) => $this->getTdAttributesForCapacite($row));
        // ->setTrAttributes(
        //     function ($row) {
        //         return [
        //             '@click' => "\$dispatch('residence-show', {id: ' $row->id '})",
        //             'style' => 'cursor: pointer; background-color: #f9f9f9;',
        //         ];
        //     });
    }

    private function getTdAttributesForCapacite($row): array
    {
        $per = $row->capacite > 0 ? round(($row->accepted / ($row->capacite)) * 100, 2) : 0;

        if ($row->capacite == 0) {
            return ['class' => 'bg-gray-50'];
        }

        return $per <= 60
            ? ['class' => 'bg-green-50']
            : ($per <= 80 ? ['class' => 'bg-yellow-50'] : ['class' => 'bg-red-50']);
    }

    public function columns(): array
    {
        return [
                  Column::make(__('livewire/tables/onou_cm_stat_table.stricture'), 'residence')
                ->format(
                    function ($value, $row, Column $column) {
                        return $row->etablissement->full_name ?? '';
                    }
                )->sortable()->searchable(),
            Column::make(__('livewire/tables/onou_cm_stat_table.capacite'), 'capacite'),
            Column::make(__('livewire/tables/onou_cm_stat_table.total'), 'total'),
            Column::make(__('livewire/tables/onou_cm_stat_table.pending'), 'pending'),
            Column::make(__('livewire/tables/onou_cm_stat_table.accepted'), 'accepted'),
            Column::make(__('livewire/tables/onou_cm_stat_table.rejected'), 'rejected'),
            Column::make(__('livewire/tables/onou_cm_stat_table.percentage'), 'capacite')
                ->format(
                    function ($value, $row, Column $column) {
                        return $value > 0 ? round(($row->accepted / $value) * 100, 2) : 0;
                    }),
        ];
    }
}
