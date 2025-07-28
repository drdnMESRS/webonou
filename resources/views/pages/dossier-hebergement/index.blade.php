<x-layouts.app :title="__('livewire/tables/onou_cm_demande_table.gestion_des_dossiers_hebergement')" >

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('livewire/tables/onou_cm_demande_table.gestion_des_dossiers_hebergement') }}
        </h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('livewire/tables/onou_cm_demande_table.gerer_les_demandes_hebergement_des_individus') }}
        </p>
    </div>



    <div class="border-b border-gray-300 dark:border-gray-600 my-4"></div>


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

    <livewire:tables.onou-cm-demande-table />
    <livewire:onou.demande-details />
</x-layouts.app>
