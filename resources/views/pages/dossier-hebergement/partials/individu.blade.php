{{-- <h1>
    @if (!empty($demande))
        <x-common.table  :data="$demande['individu']" :title="__('Détails sur l\'individu')" />
    @endif
</h1> --}}
<div class="flex flex-col md:flex-row items-start gap-6">
     @if (!empty($demande))
    <div class="md:w-1/6 w-full">
        <img src="{{ route('showPhoto', ['photoName'=>$demande['photo']??'',
         'year'=>$demande['year']??'']) }}" alt="Individu" class="icon" />
    </div>

    <div class="md:w-5/6 w-full overflow-auto">

            <x-common.table :data="$demande['individu']" :title="__('Détails sur l\'individu')" />

    </div>
      @endif
</div>
