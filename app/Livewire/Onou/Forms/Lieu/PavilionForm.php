<?php

namespace App\Livewire\Onou\Forms\Lieu;

use App\Actions\Pages\GestionLieu\CreateLieu;
use App\Models\Nc\Nomenclature;
use App\Models\Onou\Onou_cm_etablissement;
use App\Models\Onou\Onou_cm_lieu;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;

class PavilionForm extends Component
{
    public $TYPE_PAVILION = 699076;

    public $type_chambre = 699077;

    public $type_unite = 699305;

    public ?int $lieuId = null;

    public array $specialTypes = [
        'pavilion' => 699076,
        'chambre' => 699077,
        'unite' => 699305,
    ];

    public array $specialSousTypes = [
        'filles' => 699297,
        'garcons' => 699298,
    ];

    public $sous_type;

    public $Nombre_chambre;

    public $type_structure;

    public $etat;

    public $residence = null;

    public $structure_appartenance;

    public $libelle_fr;

    public $libelle_ar;

    public $surface;

    public $capacite_theorique;

    public $capacite_reelle;

    public $observation;

    public $residences = [];

    public $types = [];

    public $sous_types = [];

    public $etats = [];

    public $structures = [];

    public $chambres = [];

    protected array $rules = [
        'residence' => 'required|integer',
        'type_structure' => 'required|integer',
        'sous_type' => 'required|integer',
        'etat' => 'required|integer',
        'surface' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
        'structure_appartenance' => 'nullable|integer',
        'libelle_fr' => 'required|string|max:255',
        'libelle_ar' => 'nullable|string|max:255',
        'capacite_theorique' => 'required|integer|min:1',
        'capacite_reelle' => 'required|integer|min:0',
        'observation' => 'nullable|string|max:1000',
        'chambres' => 'nullable|array|min:1',
        'chambres.*.from' => 'nullable|integer|min:1',
        'chambres.*.to' => 'nullable|integer|min:1',
        'chambres.*.type' => 'nullable|integer|min:1',
        'chambres.*.surface' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
    ];

    #[On('lieu-edit-data')]
    #[On('lieu-edit')]
    public function loadLieu(array $lieu)
    {
        $this->lieu = $lieu;

        $details = $lieu['information_details'] ?? [];
        $this->lieuId = $lieu['id'];
        $this->residence = $lieu['etablissement'];
        $this->type_structure = $lieu['typeLieu'];
        $this->sous_type = $lieu['sousTypeLieu'];
        $this->etat = $lieu['etat'];
        $this->surface = $lieu['surface'];
        $this->loadStructures();
        $this->structure_appartenance = $lieu['parent'];
        $this->libelle_fr = $lieu['libelle_fr'] ?? '';
        $this->libelle_ar = $lieu['libelle_ar'] ?? '';
        $this->capacite_theorique = $lieu['capacite_theorique'] ?? '';
        $this->capacite_reelle = $lieu['capacite_reelle'] ?? '';
        $this->observation = $details['observation'] ?? '';

    }

    public function updatedResidence()
    {
        $this->loadStructures();
    }

    public function updatedStructureAppartenance()
    {
        $this->validatestricture();
    }

    public function updatedTypeStructure()
    {
        $this->loadStructures();
        $this->validatestricture();
        $this->validatesurface();
    }

    public function validatestricture()
    {
        $typeStructure = (int) $this->type_structure;
        if (
            is_null($this->structure_appartenance) &&
            ($typeStructure === $this->TYPE_PAVILION || $typeStructure === $this->type_chambre)
        ) {
            $this->addError('structure_appartenance', 'The structure appartenance field is required.');
        } else {
            $this->resetErrorBag('structure_appartenance');
        }
    }

    public function validatesurface()
    {
        $typeStructure = (int) $this->type_structure;
        if ($typeStructure === $this->type_chambre) {
            $this->addError('surface', 'the superficie filed is required.');
        } else {
            $this->resetErrorBag('surface');
        }
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

        $this->types = Cache::remember('nomenclature_types_lieux_filtered', 86400, function () {
            return Nomenclature::where('id_list', 339)
                ->whereIn('id', array_values($this->specialTypes)) // use only the values of the array
                ->pluck('libelle_court_fr', 'id')
                ->toArray();
        });
        $this->sous_types = Cache::remember('nomenclature_sous_types_lieux', 86400, function () {
            return Nomenclature::where('id_list', 343)
                ->whereIn('id', array_values($this->specialSousTypes))->pluck('libelle_court_fr', 'id')->toArray();
        });
        $this->loadStructures();
        $this->etats = Cache::remember('nomenclature_etat_lieux', 86400, function () {
            return Nomenclature::whereIn('id_list', [340, 350])->pluck('libelle_court_fr', 'id')->toArray();
        });
        $this->chambres = [
            ['from' => null, 'to' => null, 'type' => null, 'surface' => null],
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
            $surface_chambre = $chambre['surface'];
            if ($from && $to && $type && ! $surface_chambre) {
                $this->addError("chambres.$index.surface", 'the superficie filed is required');
            }
            // Basic field presence
            if ($from && $to && $type && $surface_chambre) {
                // Rule: from must be < to
                if ($from >= $to) {
                    $this->addError("chambres.$index.from", '"De" doit être inférieur à "À".');
                }

                // Rule: current "from" must be > previous "to"
                if ($from <= $lastTo) {
                    $this->addError("chambres.$index.from", '"De" doit être supérieur à la chambre précédente.');
                }

            }

            $lastTo = $to;
        }
    }

    public function resetPavilionForm()
    {
        $this->resetForm();
    }

    #[On('reset-pavilion-form')]
    public function resetForm()
    {
        $this->reset([
            'residence', 'etat', 'surface', 'type_structure', 'sous_type', 'structure_appartenance',
            'libelle_fr', 'libelle_ar', 'capacite_theorique', 'capacite_reelle', 'observation', 'chambres',
        ]);
        $this->chambres = [['from' => null, 'to' => null, 'type' => null, 'surface' => null]];
    }

    public function getCanAddChambreProperty()
    {
        $last = end($this->chambres);

        return ! empty($last['from']) && ! empty($last['to']) && ! empty($last['type']) && ! empty($last['surface']);
    }

    public function addChambre()
    {
        $this->chambres[] = ['from' => null, 'to' => null, 'type' => null, 'surface' => null];
    }

    public function removeChambre($index)
    {
        unset($this->chambres[$index]);
        $this->chambres = array_values($this->chambres); // reindex
    }

    public function submit()
    {
        $validated = $this->validate();

        if ((int) $validated['type_structure'] === $this->TYPE_PAVILION) {
            $this->validateChambres();
            if ($this->getErrorBag()->isNotEmpty()) {
                return;
            }
        }
        if ((int) $validated['type_structure'] === $this->type_chambre && $validated['surface'] == null) {
            $this->validatesurface();
            if ($this->getErrorBag()->isNotEmpty()) {
                return;
            }
        }

        $success = app(CreateLieu::class)->handle($validated, $this->TYPE_PAVILION, $this->type_chambre, $this->lieuId);

        $this->resetForm();
        $this->dispatch('close-lieu-modal');
        if ($success) {
            session()->flash('success', 'Lieu a été mise à jour avec succès.');
            $this->redirectRoute('onouLieu.show', navigate: true);
        }

    }

    public function render()
    {
        return view('livewire.onou.forms.lieu-form.pavilion-form');
    }
}
