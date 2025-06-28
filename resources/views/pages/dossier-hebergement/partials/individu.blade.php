<h1>
    @if(!empty($demande))
        <x-common.table  :data="$demande['individu']" :title="__('Détails sur l\'individu')" />
    @endif
</h1>
