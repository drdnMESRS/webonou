
<div>
    Data processing by the live-wire for {{$data['id'] ?? null}}
    Data processing by the live-wire for {{$data['individu']['civilite'] ?? null}}
{{$action}}
    <form wire:submit.prevent="update">
        @csrf
        @if($action === 'accept')
            @include($acceptformView, ['formFields' => $formFields])
        @elseif($action === 'reject')
            @include($rejectformView, ['formFields' => $formFields])
        @endif
        <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">
            Enregistrer
        </button>
    </form>
</div>
