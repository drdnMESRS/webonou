@if (!empty($demande))

    <button wire:click="toggleClesRemis"
        class="text-white
            {{ $demande['cles_remis'] ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-blue-700 hover:bg-blue-800' }}
            focus:ring-4 focus:outline-none
            focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center
            dark:focus:ring-blue-800 mt-2">
        {{ $demande['cles_remis'] ? 'Annuler remise des clés ' : 'Remettre les clés'   }}
    </button>
@endif

