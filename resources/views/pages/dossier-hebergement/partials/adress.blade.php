<h1>
    @if(!empty($demande))
        <x-common.table  :data="$demande['adressIndividue']" :title="__('adress')" />
    @endif
</h1>
