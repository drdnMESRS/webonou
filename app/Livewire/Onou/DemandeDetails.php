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
    #[locked]
    private ProcessCmDemandeInterface $processCmDemande;

    #[Locked]
    public $demandeId;

    #[Locked]
    public $showDemandeDetails = false;

    #[Locked]
    public ?array $demande;

    #[Locked]
    public ?string $accept_view = null;

    #[Locked]
    public ?string $reject_view = null;

    #[Locked]
    public ?string $cles_remis_view = null;

    public function mount()
    {
        $this->processCmDemande = new ProcessCmDemandeContext;
        $this->accept_view = $this->processCmDemande->getView();
        $this->reject_view = $this->processCmDemande->getView();
        $this->cles_remis_view = $this->processCmDemande->getViewClesRemis();
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
    #[On('demande-hide')]
    public function hideDemandeDetails()
    {
        $this->showDemandeDetails = false;
    }
    public function toggleClesRemis()
    {
        $this->processCmDemande = new ProcessCmDemandeContext;

        $values = ['cles_remis' => ! $this->demande['cles_remis'] || ! $this->demande['cles_remis']];

        if (! $this->demande['frais_hebregement_pay']) {
            session()->flash('error',  __('pipelines/onou/alerts.frais_hebergement_non_paye'));
            $this->redirectRoute($this->demande['rederctpage'], ['page' => $this->demande['actual_page']], navigate: true);

            return;
        }
        try {
            $this->processCmDemande->process_clesremis($this->demandeId, $values);

            session()->flash('success',  __('pipelines/onou/alerts.etat_cles_mis_a_jour'));

            $this->dispatch('refreshDatatable');

            $this->showDemandeDetails($this->demandeId);
        } catch (\Exception $e) {

            session()->flash('error', 'Validation failed: ' . $e->getMessage());
            $this->redirectRoute($this->demande['rederctpage'], ['page' => $this->demande['actual_page']], navigate: true);
            return;
        }
    }

    public function render()
    {
        return view('livewire.onou.demande-details');
    }
}
