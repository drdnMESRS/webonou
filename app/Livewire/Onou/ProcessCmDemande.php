<?php

namespace App\Livewire\Onou;

use App\Strategies\Onou\ProcessCmDemande as ProcessCmDemandeInterface;
use App\Strategies\Onou\Processing\ProcessCmDemandeContext;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

use function PHPUnit\Framework\isEmpty;

class ProcessCmDemande extends Component
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
            $this->data['civilite'] ?? null,
            $this->action
        );
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

    public function update()
    {
        // validate the input data

        $this->validate();

        $this->processCmDemande = new ProcessCmDemandeContext;

        $id = $this->data['id'] ?? null;

        // if (is_null($id) && $this->action == 'accept') {
        //     session()->flash('error', 'Aucune demande trouvée pour cette action.');
        //     $this->redirectRoute($this->data['rederctpage'], ['page' => $this->data['actual_page']], navigate: true);
        //     return;
        // }
        // if (isEmpty($this->data) && $this->action == 'create') {
        //     session()->flash('error', 'Aucune demande trouvée pour cette action.');
        //     $this->redirectRoute($this->data['rederctpage'], ['page' => $this->data['actual_page']], navigate: true);
        //     return;
        // }
        $pageact = $this->data['actual_page'];

        // prepare the new data to update
        $values = [
            $this->processCmDemande->field($this->action) => $this->field_update,
            'id_individu' => $this->data['id_individu'],
        ];
        if ($this->action === 'create') {

            $values['id_dia'] = $this->data['id_dia'] ?? null;
            $values['id_fnd'] = $this->data['id_fnd'] ?? null;
            $values['id_individu'] = $this->data['id_individu'];
        }
        try {
            // call the process method to handle the update

            $done = $this->processCmDemande->process_demande($id, $values, $this->action);
            // $this->reset(['data', 'action', 'formFields', 'field_update', 'acceptformView', 'rejectformView']);
            // dispatch an event to refresh the data table
            if (! $done) {
                session()->flash('error', 'Une erreur est survenue lors de la mise à jour de la demande.');
                $this->redirectRoute($this->data['rederctpage'], ['page' => $pageact], navigate: true);
            }
            // get the actual url to redirect

        } catch (\Exception $e) {
            session()->flash('error', 'Validation failed: ' . $e->getMessage());
            $this->redirectRoute($this->data['rederctpage'], ['page' => $pageact], navigate: true);

            return;
        }

        // get the actual url to redirect
        // $this->reset(['data', 'action', 'formFields', 'field_update', 'acceptformView', 'rejectformView']);
        session()->flash('success', 'Demande mise à jour avec succès.');
        $this->redirectRoute($this->data['rederctpage'], ['page' => $pageact], navigate: true);
    }

    public function render()
    {
        return view('livewire.onou.process-cm-demande');
    }
}
