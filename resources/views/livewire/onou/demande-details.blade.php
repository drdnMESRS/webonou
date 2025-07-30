<div>
    <div class="text-center">
        <livewire:common.loader />
    </div>

    <div x-data="{ showDemandeDetails: @entangle('showDemandeDetails') }" class=" mx-auto py-4">
        <div x-show="showDemandeDetails" class="demande-details ">
            <x-common.tab-navigation :tabs="[
                [
                    'id' => 'details',
                    'title' => __('views/livewire/onou/forms/demande_details.details_individu'),
                    'content' => [
                        ['view' => 'pages.dossier-hebergement.partials.individu', 'data' => ['demande' => $demande]],
                    ],
                ],
                [
                    'id' => 'etude',
                    'title' => __('views/livewire/onou/forms/demande_details.details_etude'),
                    'content' => [
                        ['view' => 'pages.dossier-hebergement.partials.inscription', 'data' => ['demande' => $demande]],
                    ],
                ],
                [
                    'id' => 'historique',
                    'title' => __('views/livewire/onou/forms/demande_details.historique_hebergement'),
                    'content' => [
                        [
                            'view' => 'pages.dossier-hebergement.partials.historique-heb',
                            'data' => ['demande' => $demande],
                        ],
                    ],
                ],
   [
                    'id' => 'historiqueInscription',
                    'title' => __('views/livewire/onou/forms/demande_details.historique_inscription'),
                    'content' => [
                        [
                            'view' => 'pages.dossier-hebergement.partials.historique-ins',
                            'data' => ['demande' => $demande],
                        ],
                    ],
                ],
                [
                    'id' => 'adress',
                    'title' => __('views/livewire/onou/forms/demande_details.details_adresse'),
                    'content' => [
                        ['view' => 'pages.dossier-hebergement.partials.adress', 'data' => ['demande' => $demande]],
                    ],
                ],
                [
                    'id' => 'demande',
                    'title' => __('views/livewire/onou/forms/demande_details.details_demande'),
                    'content' => [
                        ['view' => 'pages.dossier-hebergement.partials.demande-heb', 'data' => ['demande' => $demande]],
                    ],
                ],
            ]" />

            <div class="flex justify-between items-center mt-4">
                <div>
                    <x-common.modal id="accept-modal" :title="__('views/livewire/onou/forms/demande_hebergement/traitement_form.accepter')" size="auto">
                        @include($accept_view, ['data' => $demande, 'action' => 'accept'])
                    </x-common.modal>

                    <button id="open-my-modal-button" data-modal-target="accept-modal" data-modal-toggle="accept-modal"
                        class="flex items-center gap-2 block
            text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none
            focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
            dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                        type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 512 511.968"
                            fill="currentColor" shape-rendering="geometricPrecision" text-rendering="geometricPrecision"
                            image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd">
                            <path fill-rule="nonzero"
                                d="M138.851 214.679l61.342-.814 4.564 1.189c21.526 12.408 40.78 27.901 57.463 46.309 22.008-35.412 45.452-67.92 70.224-97.825 27.129-32.766 55.92-62.488 86.157-89.618l5.989-2.303h66.935l-13.49 14.99c-41.487 46.094-79.117 93.721-113.115 142.847-34.019 49.17-64.427 99.915-91.471 152.159l-8.411 16.233-7.736-16.532c-28.233-60.592-68.027-112.194-123.455-150.242l5.004-16.393zM255.984 0c38.444 0 76.181 8.561 110.833 25.201 2.239 1.071 3.193 3.782 2.121 6.022a4.578 4.578 0 01-1.275 1.596l-37.544 30.719a4.552 4.552 0 01-4.672.718c-22.254-8.057-45.751-12.108-69.42-12.108-54.27 0-105.775 21.29-144.134 59.692-38.39 38.412-59.702 89.832-59.702 144.144 0 54.281 21.29 105.743 59.691 144.123 38.423 38.402 89.832 59.713 144.145 59.713 54.227 0 105.796-21.311 144.123-59.702 38.412-38.391 59.702-89.843 59.702-144.134 0-13.2-1.211-26.197-3.75-39.162a4.57 4.57 0 011.029-3.836l33.108-41.959c1.564-1.939 4.425-2.228 6.364-.664a4.518 4.518 0 011.479 2.197C507.329 199.389 512 227.612 512 255.984c0 68.027-26.872 132.883-74.981 180.992-48.098 48.098-112.975 74.992-180.992 74.992-68.028 0-132.884-26.883-180.992-74.992l-.182-.192C26.808 388.675 0 323.969 0 255.984c0-68.027 26.872-132.883 74.981-180.992l.204-.182C123.294 26.808 188.021 0 255.984 0z" />
                        </svg>
                        {{__('views/livewire/onou/forms/demande_details.accepter_demande')}}
                    </button>
                </div>
                @include($cles_remis_view, ['demande' => $demande])
                <div>
                    <x-common.modal id="reject-modal" :title="__('views/livewire/onou/forms/demande_hebergement/traitement_form.rejeter')" size="auto">
                        @include($reject_view, ['data' => $demande, 'action' => 'reject'])
                    </x-common.modal>
                    <button id="open-reject-modal-button" data-modal-target="reject-modal"
                        data-modal-toggle="reject-modal"
                        class="flex items-center gap-2 block
            text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none
            focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
            dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                        type="button">
                      <svg class="w-5 h-5 fill-current text-white" xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 122.881 122.88">
            <path d="M61.44,0c16.966,0,32.326,6.877,43.445,17.996c11.119,11.118,17.996,26.479,17.996,43.444
                     c0,16.967-6.877,32.326-17.996,43.444C93.766,116.003,78.406,122.88,61.44,122.88c-16.966,0-32.326-6.877-43.444-17.996
                     C6.877,93.766,0,78.406,0,61.439c0-16.965,6.877-32.326,17.996-43.444C29.114,6.877,44.474,0,61.44,0L61.44,0z
                     M80.16,37.369c1.301-1.302,3.412-1.302,4.713,0c1.301,1.301,1.301,3.411,0,4.713L65.512,61.444l19.361,19.362
                     c1.301,1.301,1.301,3.411,0,4.713c-1.301,1.301-3.412,1.301-4.713,0L60.798,66.157L41.436,85.52c-1.301,1.301-3.412,1.301-4.713,0
                     c-1.301-1.302-1.301-3.412,0-4.713l19.363-19.362L36.723,42.082c-1.301-1.302-1.301-3.412,0-4.713
                     c1.301-1.302,3.412-1.302,4.713,0l19.363,19.362L80.16,37.369L80.16,37.369z
                     M100.172,22.708C90.26,12.796,76.566,6.666,61.44,6.666c-15.126,0-28.819,6.13-38.731,16.042
                     C12.797,32.62,6.666,46.314,6.666,61.439c0,15.126,6.131,28.82,16.042,38.732c9.912,9.911,23.605,16.042,38.731,16.042
                     c15.126,0,28.82-6.131,38.732-16.042c9.912-9.912,16.043-23.606,16.043-38.732
                     C116.215,46.314,110.084,32.62,100.172,22.708L100.172,22.708z"/>
        </svg>
                        {{__('views/livewire/onou/forms/demande_details.rejeter_demande')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
