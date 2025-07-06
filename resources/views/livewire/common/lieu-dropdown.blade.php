<div>
    <div class="form-group">
        <label for="lieu" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Pavillion') }}</label>
        <select id="lieu" wire:model.live="selectedPavillion"  class="mt-1 block w-full pl-3 pr-10 py-2
                text-base border-gray-300 focus:outline-none
                 focus:ring-indigo-500 focus:border-indigo-500
                 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600
                 dark:text-white"
                wire:loading.attr="disabled" wire:target="selectedPavillion">
            <option value="">{{ __('Sélectionnez un pavvilion') }}</option>
            @foreach($pavillonx as $key => $pavillon)
                <option value="{{ $key }}">{{ $pavillon }}</option>
            @endforeach
        </select>
    </div>

    @if (!empty($selectedPavillion))
    <div class="mb-4">
        <label for="chambre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Chambres:
        </label>
        <select wire:model.live="SelectedChambre" id="chambre"
                class="mt-1 block w-full pl-3 pr-10 py-2
                text-base border-gray-300 focus:outline-none
                 focus:ring-indigo-500 focus:border-indigo-500
                 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600
                 dark:text-white"
                 wire:target="SelectedChambre" name="SelectedChambre">
            <option value="">{{ __('Sélectionnez une chambre') }}</option>
            @foreach ($chambres as $key=>$item)
                <option value="{{ $key }}">{{ $item }}</option>
            @endforeach
        </select>
        <div wire:loading wire:target="SelectedChambre" class="text-sm text-gray-500 mt-1">Loading ...</div>
    </div>
    @endif
</div>
