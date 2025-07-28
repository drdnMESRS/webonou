<h1>
    @if(!empty($demande))
        <x-common.table  :data="$demande['dossierInscriptionDoctorant']" />
    @endif
</h1>
