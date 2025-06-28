<div>
    <div class="text-center">
        <livewire:common.loader />
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

]" />
</div>
    </div>
</div>

