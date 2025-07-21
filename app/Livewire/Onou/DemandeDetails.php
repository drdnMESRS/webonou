<?php

namespace App\Livewire\Onou;

use App\Actions\Pages\Dossier_demande_Hebergement\FindDemandeById;
use App\Strategies\Onou\ProcessCmDemande as ProcessCmDemandeInterface;
use App\Strategies\Onou\Processing\ProcessCmDemandeContext;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class DemandeDetails extends Component
{
    private ProcessCmDemandeInterface $processCmDemande;

    #[Locked]
    public $demandeId;

    public $showDemandeDetails = false;

    #[Locked]
    public ?array $demande;

    #[Locked]
    public ?string $accept_view = null;

    public ?string $reject_view = null;

    public function mount()
    {
        $this->processCmDemande = new ProcessCmDemandeContext;
        $this->accept_view = $this->processCmDemande->getView();
        $this->reject_view = $this->processCmDemande->getView();
    }

    #[On('demande-show')]
    public function showDemandeDetails($id)
    {
        $this->reset(['demande', 'demandeId']);
        $this->demande = (new FindDemandeById)->handle($id);
        $this->demandeId = $id;
        $this->demande['rederctpage'] = 'diaHeb.show';
        $this->showDemandeDetails = true;
    }

    public function render()
    {
        return view('livewire.onou.demande-details');
    }
}
