<div>
<div class="flex flex-col space-y-2 items-end">

    <div class="flex space-x-2">
<div class="flex space-x-6 items-center">
    <div class="flex flex-col">
        <div class="flex items-center space-x-6">
            <label class="cursor-pointer flex items-center space-x-2">
                <input type="radio" wire:model.defer="type_etudiant" value=1
                    class="h-5 w-5 border-gray-300 text-blue-500 focus:ring-blue-500"
                    checked="{{ $type_etudiant === 1 ? 'checked' : '' }}">
                <span class="text-sm font-medium text-gray-700">{{__('views/livewire/onou/forms/demande_hebergement/search_by_year_matricule.graduation')}}</span>
            </label>

            <label class="cursor-pointer flex items-center space-x-2">
                <input type="radio" wire:model.defer="type_etudiant" value=2
                    class="h-5 w-5 border-gray-300 text-green-500 focus:ring-green-500">
                <span class="text-sm font-medium text-gray-700">
                    {{__('views/livewire/onou/forms/demande_hebergement/search_by_year_matricule.post_graduation')}}
                </span>
            </label>
        </div>
        @error('type_etudiant')
            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
        @enderror
    </div>
</div>


        <div class="flex flex-col">
            <input type="text" wire:model.defer="annee_bac" placeholder="annee de bac"
                class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @error('annee_bac')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col">
            <input type="text" wire:model.defer="matricule_bac" placeholder="matricule de bac"
                class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @error('matricule_bac')
                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
            @enderror
        </div>


        <button wire:click="searchByYearMatricule" wire:target="searchByYearMatricule"
            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">

            <div wire:loading wire:target="searchByYearMatricule">
                <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="#E5E7EB" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentColor" />
                </svg>
            </div>
            {{__('views/livewire/onou/forms/demande_hebergement/search_by_year_matricule.search')}}
        </button>

    </div>
</div>
<div class="flex justify-center">
        <livewire:common.loader />
    </div>
    <div x-data="{ showDtudentDetails: @entangle('showDtudentDetails') }" class=" mx-auto py-4">
        <div x-show="showDtudentDetails" class="demande-details ">
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
                    'id' => 'doctorant',
                    'title' =>__('views/livewire/onou/forms/demande_details.details_doctorat'),
                    'content' => [
                        ['view' => 'pages.dossier-hebergement.partials.doctorat', 'data' => ['demande' => $demande]],
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
                        @include($accept_view, [
                            'data' => $demande,
                            'action' => $demande ? ($demande['id'] ? 'accept' : 'create') : '',
                        ])
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
                <div>
                    {{-- <x-common.modal id="reject-modal" title="Rejeter la demande" size="auto">

                    </x-common.modal>
                    <button id="open-reject-modal-button" data-modal-target="reject-modal"
                        data-modal-toggle="reject-modal"
                        class="block
            text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none
            focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
            dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                        type="button">
                        Rejeter le demande
                    </button> --}}
                </div>
            </div>
        </div>
    </div>
</div>
