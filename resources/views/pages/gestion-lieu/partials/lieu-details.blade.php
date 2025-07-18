
@if (!empty($lieu))
    <x-common.table :data="$lieu['information_details']" :title="__('Détails du Lieu')" />

    <div class="flex justify-between">
    <button wire:click="$dispatch('lieu-edit', { lieu: @js($lieu) })"  class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none
        focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
        Modifier
    </button>

    <button wire:click="deleteLieu"
    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none
    focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
    type="button"
    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce lieu ?')">
    Supprimer
</button>
</div>

@endif

