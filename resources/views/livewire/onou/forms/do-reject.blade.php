<div>
    @dump($formFields)
        @foreach($formFields as $field)
            <div class="mb-4">
                <label for="{{ $field['name'] }}"
                       class="block text-sm font-medium text-gray-700">
                    {{ $field['label'] }}
                </label>

            </div>
        @endforeach
</div>
