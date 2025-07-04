
<div>
    data processing by the live-wire for {{$data['id'] ?? null}}
    data processing by the live-wire for {{$data['individu']['civilite'] ?? null}}

    <form wire:submit.prevent="update">
        @csrf
        @include($formView, ['formFields' => $formFields])
        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
            Update
        </button>
    </form>
</div>
