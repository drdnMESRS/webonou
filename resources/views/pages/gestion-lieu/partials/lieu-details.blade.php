
@if (!empty($lieu))
    <x-common.table :data="$lieu['information_details']" :title="__('Détails du Lieu')" />
@endif

