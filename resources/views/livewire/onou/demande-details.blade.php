<div>
    <div class="text-center">
        <livewire:common.loader/>
    </div>

    <div x-data="{ showDemandeDetails: @entangle('showDemandeDetails') }"
         class=" mx-auto py-4">
        <div x-show="showDemandeDetails" class="demande-details ">
            <x-common.tab-navigation :tabs="[
            [
            'id' => 'details',
            'title' => __('Détails sur l\'individu'),
            'content' => [
                    ['view' => 'pages.dossier-hebergement.partials.individu', 'data' => ['demande' => $demande]],
                ]
            ],
            [
                'id' => 'etude',
                'title' => __('Détails sur l\'étude'),
                'content' => [
                   ['view' => 'pages.dossier-hebergement.partials.inscription', 'data' => ['demande' => $demande]],
                ],
            ],
            [
                'id' => 'historique',
                'title' => __('Historique d\'hebergement'),
                'content' => [
                   ['view' => 'pages.dossier-hebergement.partials.historique-heb', 'data' => ['demande' => $demande]],
                ],
            ],
            [
                'id' => 'adress',
                'title' => __('Détails sur l\'adresse de l\'individu'),
                'content' => [
                   ['view' => 'pages.dossier-hebergement.partials.adress', 'data' => ['demande' => $demande]],
                ],
            ],
            [
                'id' => 'demande',
                'title' => __('Détails sur la demande d\'hébérgement'),
                'content' => [
                   ['view' => 'pages.dossier-hebergement.partials.demande-heb', 'data' => ['demande' => $demande]],
                ],
            ],

]"/>

            <x-common.modal id="my-modal" title="My Awesome Modal" size="lg">
                @include($view, ['data'=>$demande])
            </x-common.modal>

            <button id="open-my-modal-button"
                    data-modal-target="my-modal"
                    data-modal-toggle="my-modal" class="block
            text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none
            focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
            dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    type="button">
                Toggle modal
            </button>
        </div>

    </div>


</div>

