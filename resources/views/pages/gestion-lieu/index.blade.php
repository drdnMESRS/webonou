<x-layouts.app :title="__('Gestion_dossier_hebergement')" xmlns:livewire="http://www.w3.org/1999/html">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{ __('Gestion des Lieu') }}
        </h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Gérer les lieu et les structures') }}
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


    <div class="mb-6">
        <livewire:tables.lieu-table />
        <x-common.tab-navigation :tabs="[
            [
                'id' => 'lieu',
                'title' => __('Lieu'),
                'content' => 'test'
            ],
            [
                'id' => 'structure',
                'title' => __('Structure'),
                'content' => 'test'
            ]
        ]" />
    </div>
</x-layouts.app>
