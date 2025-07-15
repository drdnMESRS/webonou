<?php

namespace App\Livewire\Onou\Forms\Lieu;

use Livewire\Attributes\Locked;
use Livewire\Component;
use App\Models\Nc\Nomenclature;
use App\Models\Onou\Onou_cm_etablissement;
use App\Models\Onou\Onou_cm_lieu;
use App\Actions\Pages\GestionLieu\CreateLieu;
use Illuminate\Support\Facades\Cache;
class PavilionForm extends Component
{

    #[Locked]
    public $TYPE_PAVILION = 699076,$type_chambre=699077,$type_unite=699305;


    public  $sous_type,$Nombre_chambre	;
    public  $type_structure, $residence=null ;
    public $structure_appartenance;
    public $libelle_fr, $libelle_ar, $capacite_theorique, $capacite_reelle, $observation;

    #[Locked]
    public $residences = [];
    #[Locked]
    public $types = [];
    #[Locked]
    public $sous_types = [];
    #[Locked]
    public $structures = [];

    public $chambres = [];
    #[Locked]
        protected array $rules = [
        'residence' => 'required|integer',
        'type_structure' => 'required|integer',
        'sous_type' => 'nullable|integer',
        'structure_appartenance' => 'nullable|integer',
        'libelle_fr' => 'required|string|max:255',
        'libelle_ar' => 'required|string|max:255',
        'capacite_theorique' => 'nullable|integer|min:1',
        'capacite_reelle' => 'nullable|integer|min:0',
        'observation' => 'nullable|string|max:1000',
        'chambres' => 'nullable|array|min:1',
        'chambres.*.from' => 'nullable|integer|min:1',
        'chambres.*.to' => 'nullable|integer|min:1',
        'chambres.*.type' => 'nullable|integer|min:1',
    ];

public function updatedResidence()
{
    $this->loadStructures();
}

public function updatedTypeStructure()
{
    $this->loadStructures();
}
public function loadStructures()
{
       $type = (int) $this->type_structure;

    $typeLieu = match ($type) {
        $this->TYPE_PAVILION => $this->type_unite,
        $this->type_chambre => $this->TYPE_PAVILION,
        default => 0,
    };
    $this->structures = Onou_cm_lieu::where('etablissement', $this->residence)
        ->where('type_lieu', $typeLieu)
        ->pluck('libelle_fr', 'id')
        ->toArray();
}
    public function mount()
    {
        $this->residences = Onou_cm_etablissement::pluck('denomination_fr', 'id')->toArray();

        $this->types = Cache::remember('nomenclature_types_lieux', 86400, function () {
            return Nomenclature::where('id_list', 339)->pluck('libelle_court_fr', 'id')->toArray();
        });

        $this->sous_types = Cache::remember('nomenclature_sous_types_lieux', 86400, function () {
            return Nomenclature::where('id_list', 343)->pluck('libelle_court_fr', 'id')->toArray();
        });
         $this->loadStructures();
        $this->chambres = [
            ['from' => null, 'to' => null, 'type' => null],
        ];


    }
public function updatedChambres()
{
    $this->resetErrorBag(); // clear old errors
    $this->validateChambres();
}

public function validateChambres()
{
    $lastTo = 0;

    foreach ($this->chambres as $index => $chambre) {
        $from = (int) $chambre['from'];
        $to = (int) $chambre['to'];
        $type = $chambre['type'];

        // Basic field presence
        if ($from && $to && $type) {
            // Rule: from must be < to
            if ($from >= $to) {
                $this->addError("chambres.$index.from", '"De" doit être inférieur à "À".');
            }

            // Rule: current "from" must be > previous "to"
            if ($from <= $lastTo) {
                $this->addError("chambres.$index.from", '"De" doit être supérieur à la chambre précédente.');
            }
        }

        if (!is_numeric($type) || $type <= 0) {
            $this->addError("chambres.$index.type", 'Type doit être un nombre positif.');
        }

        $lastTo = $to;
    }
}
    public function resetPavilionForm()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'residence', 'type_structure', 'sous_type', 'structure_appartenance',
            'libelle_fr', 'libelle_ar', 'capacite_theorique', 'capacite_reelle', 'observation', 'chambres'
        ]);
        $this->chambres = [['from' => null, 'to' => null, 'type' => null]];
    }

    public function getCanAddChambreProperty()
    {
        $last = end($this->chambres);
        return !empty($last['from']) && !empty($last['to']) && !empty($last['type']);
    }

    public function addChambre()
    {
        $this->chambres[] = ['from' => null, 'to' => null, 'type' => null];
    }

    public function removeChambre($index)
    {
        unset($this->chambres[$index]);
        $this->chambres = array_values($this->chambres); // reindex
    }

    public function submit()
    {
    $validated = $this->validate();

    if ((int)$validated['type_structure'] === $this->TYPE_PAVILION) {
        $this->validateChambres();
        if ($this->getErrorBag()->isNotEmpty()) return;
    }


    app(CreateLieu::class)->handle($validated, $this->TYPE_PAVILION, $this->type_chambre);

    $this->resetForm();
    $this->dispatch('close-lieu-modal');


    }

    public function render()
    {
        return view('livewire.onou.forms.lieu-form.pavilion-form');
    }
}
