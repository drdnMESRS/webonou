<x-layouts.app :title="__('Gestion_dossier_hebergement')" xmlns:livewire="http://www.w3.org/1999/html">

    <div class="flex justify-between items-center mb-4">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ __('Gestion des Lieux') }}
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Gérer les lieux et les structures') }}
            </p>
        </div>

        <div>
            <x-common.modal id="lieu-modal" title="Lieu Details" size="auto" padding="5">
                <livewire:onou.forms.lieu.pavilion-form />
            </x-common.modal>

            <button
    id="open-my-modal-button"
    data-modal-target="lieu-modal"
    data-modal-toggle="lieu-modal"
    onclick="Livewire.dispatch('reset-pavilion-form')"
    class="block text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none
           focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
           dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
    type="button">
    <i class="fa fa-plus"></i> Ajouter
    </button>
        </div>


    </div>

    <!-- if ssession contains 'success' message, display it -->


 @if (session()->has('success'))
        <x-common.alert type="success"
                        title="Success"
                        message="{{ session('success') }}"
                        class="mt-8 shadow-lg"
                        id="my-custom-alert" />
    @endif
    @if (session()->has('error'))
        <x-common.alert type="danger"
                        title="Error"
                        message="{{ session('error') }}"
                        class="mt-8 shadow-lg"
                        id="my-custom-alert" />
    @endif

        <livewire:tables.lieu-table />
        <livewire:onou.lieu-details />


    </div>
</x-layouts.app>
