<?php

namespace App\Livewire\Onou;

use App\Actions\Pages\Dossier_demande_Hebergement\FindDemandeById;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class DemandeDetails extends Component
{
    #[Locked]
    public $demandeId;

    #[Locked]
    public $showDemandeDetails = false;

    #[Locked]
    public ?array $demande;

    #[On('demande-show')]
    public function showDemandeDetails($id)
    {
        $this->demande = (new FindDemandeById)->handle($id);
        $this->demandeId = $id;
        $this->showDemandeDetails = true;
    }

    public function render()
    {
        return view('livewire.onou.demande-details');
    }
}
