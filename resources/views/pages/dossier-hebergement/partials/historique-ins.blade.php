<h1>
    @if(!empty($demande))

        @foreach($demande['historiqueInscription'] as $Item)
            <x-common.table  :data="collect($Item)->toArray()"/>
            <div class="flex items-center justify-center my-4">
                <hr class="w-full border-t border-gray-300 dark:border-gray-600">
            </div>
        @endforeach

    @endif
</h1>
