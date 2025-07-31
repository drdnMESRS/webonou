<?php

namespace App\Livewire\Onou;

use App\Strategies\Onou\ProcessCmDemande as ProcessCmDemandeInterface;
use App\Strategies\Onou\Processing\ProcessCmDemandeContext;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class EnregistrerCmDemande extends Component
{
    #[locked]
    private ProcessCmDemandeInterface $processCmDemande;

    public array $data;

    #[locked]
    public ?string $action = null;

    #[locked]
    public ?array $formFields = null;

    public ?int $field_update = null;

    #[locked]
    public ?string $acceptformView = null;

    #[locked]
    public ?string $rejectformView = null;

    protected function rules()
    {
        $this->processCmDemande = new ProcessCmDemandeContext;

        return $this->processCmDemande->rules($this->action);
    }

    public function mount()
    {
        $this->processCmDemande = new ProcessCmDemandeContext;
        $this->formFields = $this->processCmDemande->formFields(
            $this->data['individu']['civilite'] ?? null, $this->action);
        $this->acceptformView = $this->processCmDemande->getFormView()['accept'] ?? null;
        $this->rejectformView = $this->processCmDemande->getFormView()['reject'] ?? null;

    }

    #[On('ChambrefieldUpdateChanged')]
    public function updatedFieldUpdate($value)
    {
        // dispatch an event to update the field in the form
        if (! empty($value)) {
            $this->field_update = $value;
        } else {
            $this->field_update = null;
        }
    }

    public function CreateOrupdate()
    {
        // validate the input data
        $this->validate();

        $this->processCmDemande = new ProcessCmDemandeContext;

        $id = $this->data['id'] ?? null;

        if (is_null($id)) {
            session()->flash('error',  __('pipelines/onou/alerts.etudiant_not_inscrie'));
            $this->redirectRoute('diaHeb.show', ['page' => $this->data['actual_page']], navigate: true);

            return;
        }
        $pageact = $this->data['actual_page'];

        // prepare the new data to update
        $values = [
            $this->processCmDemande->field($this->action) => $this->field_update,
        ];
        try {
            // call the process method to handle the update
            $done = $this->processCmDemande->process_demande($id, $values, $this->action);
            $this->reset(['data', 'action', 'formFields', 'field_update', 'acceptformView', 'rejectformView']);
            // dispatch an event to refresh the data table
            if (! $done) {
                session()->flash('error',  __('pipelines/onou/alerts.erreur_survenue_lors_mise_a_jour_demande'));
                $this->redirectRoute('diaHeb.show', ['page' => $pageact], navigate: true);
            }
            // get the actual url to redirect

        } catch (\Exception $e) {
            session()->flash('error', 'Validation failed: '.$e->getMessage());
            $this->redirectRoute('diaHeb.show', ['page' => $pageact], navigate: true);

            return;
        }

        // get the actual url to redirect

        session()->flash('success', __('pipelines/onou/alerts.demande_mise_a_jour_succes'));
        $this->redirectRoute('diaHeb.show', ['page' => $pageact], navigate: true);
    }

    public function render()
    {
        return view('livewire.onou.enregistre-cm-demande');
    }
}
