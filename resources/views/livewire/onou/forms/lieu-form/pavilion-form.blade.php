
<div class="p-6 space-y-6">
    <form wire:submit.prevent="submit" class="space-y-4">



        {{-- Residence --}}
        <div>
            <label for="residence" class="block text-sm font-medium text-gray-700">
                Résidence
            </label>
            <select id="residence" wire:model.live="residence"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Sélectionner une résidence</option>

                 @foreach ($residences as $id => $label)
                    <option value="{{ $id }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('residence') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        {{-- Type de structure --}}
<div>
    <label class="block text-sm font-medium text-gray-700">
        Type de la structure
    </label>
    <select id="type_structure" wire:model.live="type_structure"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Sélectionner</option>
        @foreach ($types as $id => $label)
            <option value="{{ $id }}">{{ $label }}</option>
        @endforeach
    </select>
    @error('type_structure') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
</div>

        {{-- Sous type --}}
        <div>
            <label for="sous_type" class="block text-sm font-medium text-gray-700">
                Sous type
            </label>
            <select id="sous_type" wire:model.live="sous_type"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Sélectionner</option>

                 @foreach ($sous_types as $id => $label)
                   <option value="{{ $id }}">{{ $label }}</option>
                 @endforeach
            </select>
            @error('sous_type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Structure d'appartenance --}}
        @if((int)$type_structure !== $type_unite)
        <div>
            <label for="structure_appartenance" class="block text-sm font-medium text-gray-700">
                Structure d'appartenance
            </label>
            <select id="structure_appartenance" wire:model.live="structure_appartenance"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Sélectionner</option>
                @foreach ($structures as $id => $label)
                   <option value="{{ $id }}" {{ $structure_appartenance == $id ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach

            </select>
            @error('structure_appartenance') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        @endif
                {{-- Etats --}}
        <div>
            <label for="structure_appartenance" class="block text-sm font-medium text-gray-700">
                Etat de lieu
            </label>
            <select id="etat" wire:model.live="etat"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Sélectionner</option>
                @foreach ($etats as $id => $label)
                    <option value="{{ $id }}">{{ $label }}</option>
                @endforeach

            </select>
            @error('etat') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        {{-- Libellé FR / AR --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Libellé Fr</label>
                <input type="text" wire:model.live="libelle_fr"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('libelle_fr') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Libellé Ar</label>
                <input type="text" wire:model.live="libelle_ar"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('libelle_ar') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
        </div>

        {{-- Capacités --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Capacité Théorique</label>
                <input type="number" min=1 wire:model.live="capacite_theorique"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('capacite_theorique') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Capacité Réelle</label>
                <input type="number" min=0 wire:model.live="capacite_reelle"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            @error('capacite_reelle') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

        </div>

        <!-- Show only if type is Pavillon (you can match by ID or name) -->
@if((int)$type_structure === $TYPE_PAVILION)
<!--    <div>
                <label class="block text-sm font-medium text-gray-700">Nombre de chambre	</label>
                <input type="number" min=1 wire:model.live="Nombre_chambre"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
    </div>
    -->
    <div class="mt-4 space-y-2">
        <label class="block font-bold mb-2">Chambres</label>

        @foreach($chambres as $index => $chambre)
            <div class="flex gap-4 w-full">

               {{-- De --}}
<div class="w-1/4">
    <label class="block text-sm font-medium text-gray-700">De</label>
    <input type="number" min="1" wire:model.live="chambres.{{ $index }}.from"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
    @error("chambres.$index.from")
        <span class="text-red-600 text-xs">{{ $message }}</span>
    @enderror
</div>

{{-- À --}}
<div class="w-1/4">
    <label class="block text-sm font-medium text-gray-700">À</label>
    <input type="number" min="1" wire:model.live="chambres.{{ $index }}.to"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
    @error("chambres.$index.to")
        <span class="text-red-600 text-xs">{{ $message }}</span>
    @enderror
</div>

{{-- Type --}}
<div class="w-1/4">
    <label class="block text-sm font-medium text-gray-700">Position</label>
    <input type="text" wire:model.live="chambres.{{ $index }}.type"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
    @error("chambres.$index.type")
        <span class="text-red-600 text-xs">{{ $message }}</span>
    @enderror
</div>

                {{-- Button --}}
                <div class="w-1/4 flex {{ $errors->has("chambres.$index.from") || $errors->has("chambres.$index.to") || $errors->has("chambres.$index.type") ? 'items-center' : 'items-end' }} pb-1">
                    @if($loop->last)
                             <button type="button" wire:click="addChambre"
        @if (!$this->canAddChambre || $errors->has('chambres.*')) disabled @endif
        class="inline-flex items-center px-3 py-2 border text-sm font-medium rounded-md
            @if (!$this->canAddChambre || $errors->has('chambres.*'))
                border-gray-300 text-gray-400 bg-gray-100 cursor-not-allowed
            @else
                border-green-500 text-green-600 hover:bg-green-50
            @endif">
        +
    </button>
                    @else
                        <button type="button" wire:click="removeChambre({{ $index }})"
                            class="inline-flex items-center px-3 py-2 border border-red-500 text-red-600 text-sm font-medium rounded-md hover:bg-red-50">
                            −
                        </button>
                    @endif
                </div>

            </div>
        @endforeach
    </div>
@endif

        {{-- Observation --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Observation</label>
            <textarea wire:model.live="observation" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center justify-between pt-4">
            <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">
            <div wire:loading wire:target="submit">
                <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                </svg>
            </div>
            Enregistrer
        </button>
            <button type="button" wire:click="resetForm"
                class="text-sm text-gray-600 hover:text-gray-900">
                Reset
            </button>
        </div>
    </form>
</div>
