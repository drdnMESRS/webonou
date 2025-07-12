<?php

namespace App\Livewire\Tables\Traits\LieuTable;


use App\Models\Nc\Nomenclature;
use App\Models\Onou\Onou_cm_etablissement;
use App\Models\Onou\Onou_cm_lieu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

Trait LieuTrait
{

    public function filters(): array
    {
        return [
            'type' => SelectFilter::make('Type')
                ->options(
                    Cache::remember(
                        'nomenclature_types_lieux',
                        60 * 60 * 24, // Cache for 24 hours
                        function () {
                            return Nomenclature::query()
                                ->byListId(339) // Assuming 339 is the list ID for types of lieux
                                ->get()
                                ->mapWithKeys(
                                    function ($item) {
                                        return [
                                            $item->id => $item->full_name
                                        ];
                                    }
                                )->toArray();
                        }
                    )
                )
                ->filter(function (Builder $builder, $value) {
                    return $builder->where('type_lieu', $value);
                }),
            'sous_type' => SelectFilter::make('Sous Type')
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
                                            $item->id => $item->full_name
                                        ];
                                    }
                                )->toArray();
                        })
                )
                ->filter(function (Builder $builder, $value) {
                    return $builder->where('sous_type_lieu', $value);
                }),

                'parent' => SelectFilter::make('Parent')
                    ->options(
                        Onou_cm_etablissement::query()
                            ->get()
                            ->mapWithKeys(
                                function ($item) {
                                    return [
                                        $item->id => $item->full_name
                                    ];
                                }
                            )->toArray()
                    )
                    ->filter(function (Builder $builder, $value) {
                        return $builder->where('etablissement', $value);
                    }),
        ];
    }


}
