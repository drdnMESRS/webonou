<?php

namespace App\Livewire\Onou\Forms;

use App\Actions\Pages\Dossier_demande_Hebergement\FindStudentByYearMatricule;
use App\Strategies\Onou\ProcessCmDemande as ProcessCmDemandeInterface;
use App\Strategies\Onou\Processing\ProcessCmDemandeContext;
use Livewire\Attributes\Locked;
use Livewire\Component;

class SearchByYearMatricule extends Component
{
    #[locked]
    private ProcessCmDemandeInterface $processCmDemande;

    public ?bool $showDtudentDetails = false;

    public $annee_bac = 2024;

    public $matricule_bac;

    public $demande = [];

    #[Locked]
    public ?string $accept_view = null;

    #[Locked]
    public ?string $reject_view = null;

    public $rules;

       public int $type_etudiant = 1;
    protected $messages = [
        'annee_bac.required' => 'L\'année du bac est obligatoire.',
        'annee_bac.digits' => 'L\'année du bac doit être composée de 4 chiffres.',
        'matricule_bac.required' => 'Le matricule du bac est obligatoire.',
    ];

    public function mount()
    {
        $this->processCmDemande = new ProcessCmDemandeContext;
        $currentYear = date('Y');
        $this->rules = [
            'annee_bac' => 'required|digits:4|integer|min:1900|max:' . ($currentYear + 1),
            'matricule_bac' => 'required|min:3|max:20',
        ];
        $this->accept_view = $this->processCmDemande->getView();
    }

    public function searchByYearMatricule()
    {
        $this->dispatch('loader-show');
        $this->validate();

        try {
              $this->showDtudentDetails = false;
                $this->demande = [];
                
         //   dd($this->type_etudiant);
            $this->demande = (new FindStudentByYearMatricule)->handle($this->annee_bac, $this->matricule_bac,$this->type_etudiant);

            $this->demande['rederctpage'] = 'diaHeb.create';
            $this->showDtudentDetails = true;
        } catch (\Exception $e) {

            session()->flash('error', 'Validation failed: ' . $e->getMessage());
              $this->redirectRoute('diaHeb.create');
        }
    }

    public function render()
    {
        return view('livewire.onou.forms.demande-hebergement.search-by-year-matricule');
    }
}
