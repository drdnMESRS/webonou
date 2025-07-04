<?php

namespace App\Livewire\Onou;

use App\Livewire\Tables\OnouCmDemandeTable;
use App\Strategies\Onou\ProcessCmDemande as ProcessCmDemandeInterface;
use App\Strategies\Onou\Processing\ProcessCmDemandeContext;
use Livewire\Component;

class ProcessCmDemande extends Component
{
    private ProcessCmDemandeInterface $processCmDemande;

    public array $data;

    public ?array $formFields = null;

    public ?int $field_update = null;

    public ?string $formView = null;

    public function mount()
    {
        $this->processCmDemande = new ProcessCmDemandeContext;
        $this->formFields = $this->processCmDemande->formFields($this->data['individu']['civilite'] ?? null);
        $this->formView = $this->processCmDemande->getFormView();
    }

    public function update()
    {
        $this->processCmDemande = new ProcessCmDemandeContext;

        $id = $this->data['id'] ?? null;

        if (is_null($id)) {
            session()->flash('error', 'Aucune demande trouvée pour cette action.');
            $this->redirectRoute('diaHeb.show',['page'=>$this->data['actual_page']], navigate: true);
            return;
        }
        // prepare the new data to update
        $values = [
            $this->processCmDemande->field() => $this->field_update,
        ];
        // call the process method to handle the update
        $done = $this->processCmDemande->process_demande($id, $values);
        // dispatch an event to refresh the data table
        if (!$done) {
            session()->flash('error', 'Une erreur est survenue lors de la mise à jour de la demande.');
            $this->redirectRoute('diaHeb.show',['page'=>$this->data['actual_page']], navigate: true);
        }

        // get the actual url to redirect

        session()->flash('success', 'Demande mise à jour avec succès.');
        $this->redirectRoute('diaHeb.show',['page'=>$this->data['actual_page']], navigate: true);
    }

    public function render()
    {
        return view('livewire.onou.process-cm-demande');
    }
}
