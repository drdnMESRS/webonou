<h1>
    @if(!empty($demande))
        <x-common.table  :data="$demande['dossierInscriptionAdministrative']" />
    @endif
</h1>
