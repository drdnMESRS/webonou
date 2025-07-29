<div>

    <div class="text-center">
        <livewire:common.loader/>
    </div>

    <div x-data="{ showLieuDetails: @entangle('showLieuDetails') }"
         class=" mx-auto py-4">
        <div x-show="showLieuDetails" class="lieu-details ">
            <x-common.tab-navigation :tabs="[
                [
                'id' => 'lieu-details',
                'title' =>__('livewire/tables/lieu_table.details'),
                'content' => [
                    ['view' => 'pages.gestion-lieu.partials.lieu-details',
                    'data' => ['lieu' => $lieu]],
                ]
                ],
            ]
            "/>
        </div>
    </div>

</div>
