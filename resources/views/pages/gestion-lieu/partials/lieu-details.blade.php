
@if (!empty($lieu))
    <x-common.table :data="$lieu['information_details']" :title="__('Détails du Lieu')" />

           <button wire:click="$dispatch('lieu-edit')" class="block
                   text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none
                   focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
                   dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" type="button">
                       Modifier Lieu
            </button>

@endif

