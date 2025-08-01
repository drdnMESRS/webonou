<x-layouts.app :title=" __('views/livewire/onou/forms/demande_hebergement/search_by_year_matricule.Creer_demande')" >

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            {{__('views/livewire/onou/forms/demande_hebergement/search_by_year_matricule.Creer_demande')}}
        </h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
             {{__('views/livewire/onou/forms/demande_hebergement/search_by_year_matricule.Creer_demande')}}
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
 <livewire:onou.forms.search-by-year-matricule />
</x-layouts.app>

