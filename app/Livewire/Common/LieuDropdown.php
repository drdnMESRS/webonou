<?php

namespace App\Livewire\Common;

use App\Actions\Sessions\RoleManagement;
use App\Livewire\Onou\ProcessCmDemande;
use App\Models\Onou\Onou_cm_lieu;
use Livewire\Component;

class LieuDropdown extends Component
{
    public array $pavillonx = [];

    public int $selectedPavillion;

    public int $SelectedChambre;

    public array $chambres = [];

    public function mount(array $pavillon = [], array $chambre = [])
    {
        $this->pavillonx = $this->chambres = cache()->remember(
            'pavillion_'.auth()->id(),
            60 * 60,
            function () {
                return Onou_cm_lieu::pavillion()
                    ->byEtablissement(app(RoleManagement::class)->get_active_role_etablissement())
                    ->pluck('libelle_fr', 'id')
                    ->toArray();
            });
    }

    public function updatedSelectedPavillion($value)
    {
        $this->loadChambres($value);
    }

    public function updatedSelectedChambre($value)
    {
        $this->dispatch('ChambrefieldUpdateChanged', $value)->to(ProcessCmDemande::class);
    }

    private function loadChambres($pavillionId)
    {
        $this->chambres = cache()->remember(
            'chambres_pavillion_'.$pavillionId,
            120,
            function () use ($pavillionId) {
                return Onou_cm_lieu::chamber()
                    ->byEtablissement(app(RoleManagement::class)->get_active_role_etablissement())
                    ->where('lieu', $pavillionId)
                    ->pluck('libelle_fr', 'id')
                    ->toArray();
            }
        );

    }

    public function render()
    {
        return view('livewire.common.lieu-dropdown');
    }
}
