<div>

    <x-common.grid>
        <x-common.card>
            <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Domaine') }}</label>
            <div class="form-group">
                <select id="domain" wire:model.live="selectedDomain"  class="mt-1 block w-full pl-3 pr-10 py-2
                text-base border-gray-300 focus:outline-none
                 focus:ring-indigo-500 focus:border-indigo-500
                 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600
                 dark:text-white"
                        wire:loading.attr="disabled" wire:target="selectedDomain">
                    <option value="">{{ __('Sélectionnez un domaine') }}</option>
                    @foreach($domains as $key => $pavillon)
                        <option value="{{ $key }}">{{ $pavillon }}</option>
                    @endforeach
                </select>
            </div>
        </x-common.card>
        <x-common.card>

                <x-common.card>
                    <div wire:loading>
                        <i class="fas fa-spinner fa-spin text-gray-500"></i>
                    </div>
                    @if (!empty($selectedDomain))
                        <div class="mb-4">
                            <label for="filiere" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Filiere') }}
                            </label>
                            <select wire:model.live="SelectedFiliere" id="filiere"
                                    class="mt-1 block w-full pl-3 pr-10 py-2
                                            text-base border-gray-300 focus:outline-none
                                             focus:ring-indigo-500 focus:border-indigo-500
                                             sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600
                                             dark:text-white"
                                    wire:target="SelectedFiliere" name="SelectedFiliere"  wire:change="filiereFilterChanged" >
                                @foreach ($filieres as $key=>$item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <div wire:loading wire:target="SelectedFiliere" class="text-sm text-gray-500 mt-1">Loading ...</div>
                        </div>
                    @endif
                </x-common.card>

        </x-common.card>
    </x-common.grid>



</div>
