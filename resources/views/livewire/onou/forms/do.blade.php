@foreach($formFields as $field)
    <div class="mb-4">
        <label for="{{ $field['name'] }}"
               class="block text-sm font-medium text-gray-700">
            {{ $field['label'] }}
        </label>
        @if($field['type'] === 'select')
            <select id="{{ $field['name'] }}" wire:model="field_update"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @foreach($field['options'] as $key => $option)
                    <option value="{{ $key }}">{{ $option }}</option>
                @endforeach
            </select>
            @error($field['name']) <span class="text-red-500">{{ $message }}</span> @enderror
        @else
            <input type="{{ $field['type'] }}" id="{{ $field['name'] }}"
                   wire:model="{{ $field['name'] }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error($field['name']) <span class="text-red-500">{{ $message }}</span> @enderror
        @endif
    </div>
@endforeach
