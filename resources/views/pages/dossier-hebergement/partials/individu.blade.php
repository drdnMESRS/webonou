{{-- <h1>
    @if(!empty($demande))
        <x-common.table  :data="$demande['individu']" :title="__('Détails sur l\'individu')" />
    @endif
</h1> --}}
<div class="flex flex-col md:flex-row items-start gap-5">

    <div class="md:w-1/5 w-full">
        <img   src="{{ $demande['photo']??''}}"
             alt="Photo Étudiant"
             class="w-full rounded shadow">
    </div>
    <div class="md:w-4/5 w-full overflow-auto">
        @if (!empty($demande))
            <x-common.table :data="$demande['individu']" :title="__('Détails sur l\'individu')" />
        @endif
    </div>
</div>
