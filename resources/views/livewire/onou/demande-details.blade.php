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

            <div class="flex justify-between items-center mt-4">
               <div>
                   <x-common.modal id="accept-modal" title="Affectation au residence" size="auto">
                       @include($accept_view, ['data'=>$demande, 'action'=>'accept'])
                   </x-common.modal>

                   <button id="open-my-modal-button"
                           data-modal-target="accept-modal"
                           data-modal-toggle="accept-modal" class="block
            text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none
            focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
            dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" type="button">
                       Accepter le demande
                   </button>
               </div>
              <div>
                  <x-common.modal id="reject-modal" title="Rejet de la demande" size="auto">
                      @include($reject_view, ['data'=>$demande, 'action'=>'reject'])
                  </x-common.modal>
                  <button id="open-reject-modal-button"
                          data-modal-target="reject-modal"
                          data-modal-toggle="reject-modal" class="block
            text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none
            focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
            dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" type="button">
                      Rejeter le demande
                  </button>
              </div>
            </div>
        </div>
    </div>


</div>

