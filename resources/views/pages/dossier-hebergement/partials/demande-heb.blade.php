<h1>
    @if(!empty($demande))


        @foreach(session('_alert') as $check)
            @if(!empty($check))
                <x-common.alert type="{{$check['status'] ?? ''}}"
                                title="{{$check['title'] ?? ''}}"
                                message="{{$check['message'] ?? ''}}"
                                class="mt-8 shadow-lg"
                                id="my-custom-alert" />
            @endif

        @endforeach

        <x-common.table :data="$demande['demandeHebergement']" :title="__('adress')" />
    @endif
</h1>
