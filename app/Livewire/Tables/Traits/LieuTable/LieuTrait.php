<?php

namespace App\Livewire\Tables\Traits\LieuTable;

use App\Models\Nc\Nomenclature;
use App\Models\Onou\Onou_cm_etablissement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

trait LieuTrait
{
    public array $specialTypes = [
        'pavilion' => 699076,
        'chambre' => 699077,
        'unite' => 699305,
    ];

    public function filters(): array
    {
        return [
            'etablissement' => SelectFilter::make(__('livewire/tables/lieu_table.residence'))
                ->options(
                    Onou_cm_etablissement::query()
                        ->get()
                        ->mapWithKeys(
                            function ($item) {
                                return [
                                    $item->id => $item->full_name,
                                ];
                            }
                        )->toArray()
                )
                ->filter(function (Builder $builder, $value) {
                    return $builder->where('etablissement', $value);
                }),
            'type' => SelectFilter::make(__('livewire/tables/lieu_table.type'))
                ->options(
                    Cache::remember(
                        'nomenclature_types_lieux',
                        60 * 60 * 24, // Cache for 24 hours
                        function () {
                            return Nomenclature::query()
                                ->byListId(339) // Assuming 339 is the list ID for types of lieux
                                ->whereIn('id', array_values($this->specialTypes))
                                ->get()
                                ->mapWithKeys(
                                    function ($item) {
                                        return [
                                            $item->id => $item->full_name,
                                        ];
                                    }
                                )->toArray();
                        }
                    )
                )
                ->filter(function (Builder $builder, $value) {
                    return $builder->where('type_lieu', $value);
                }),
            'sous_type' => SelectFilter::make(__('livewire/tables/lieu_table.sous_type'))
                ->options(
                    Cache::remember(
                        'nomenclature_sous_types_lieux',
                        60 * 60 * 24, // Cache for 24 hours
                        function () {
                            return Nomenclature::query()
                                ->byListId(343) // Assuming 340 is the list ID for sous types of lieux
                                ->get()
                                ->mapWithKeys(
                                    function ($item) {
                                        return [
                                            $item->id => $item->full_name,
                                        ];
                                    }
                                )->toArray();
                        })
                )
                ->filter(function (Builder $builder, $value) {
                    return $builder->where('sous_type_lieu', $value);
                }),
            'etat' => SelectFilter::make(__('livewire/tables/lieu_table.etat'))
                ->options(
                    Cache::remember(
                        'nomenclature_etat_lieux',
                        60 * 60 * 24, // Cache for 24 hours
                        function () {
                            return Nomenclature::query()
                                ->byListId(340, 350)
                                ->get()
                                ->mapWithKeys(
                                    function ($item) {
                                        return [
                                            $item->id => $item->full_name,
                                        ];
                                    }
                                )->toArray();
                        })
                )
                ->filter(function (Builder $builder, $value) {
                    return $builder->where('etat', $value);
                }),

        ];
    }
}
