<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;
use App\Models\Onou\Onou_cm_lieu;
use App\Models\Onou\Onou_cm_demande;
use App\Models\Onou\Onou_cm_etablissement;
use App\Models\Onou\vm_heb_processing_by_ru;

class ExportData extends Component
{
    public string $table;
    public array $rows = [], $headings = [];
    public array $specialTypes = [
        'pavilion' => 699076,
        'chambre' => 699077,
        'unite' => 699305,
    ];


    public function exportExcel()
    {

        try {
            $info = $this->getModelInfo($this->table);

             $this->rows = $info['query']
                // ->limit(100)
                 ->get()
                 ->map($info['map'])
                 ->toArray();
            $this->headings = $info['columns'];

            return Excel::download(new GenericExport($this->rows, $this->headings),$info['filename']);
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function getModelInfo(string $model): array
    {
        return match ($model) {
            'lieux' => $this->getLieuxExportInfo(),
            'etudiants' => $this->getEtudiantsExportInfo(),
            'residences' => $this->getResidencesExportInfo(),
            'statistiques' => $this->getStatistiquesExportInfo(),
            default => throw new \Exception("Modèle inconnu : $model"),
        };
    }

    private function getLieuxExportInfo(): array
    {
        $query = Onou_cm_lieu::query()
            ->select('onou_cm_lieu.*')
            ->withCount(['children', 'affectation'])
            ->with(['etatLieu', 'typeLieu', 'etablissementLieu', 'sousTypeLieu', 'parent'])
            ->whereIn('type_lieu', $this->specialTypes)
            ->remember(60 * 60 * 3);

        return [
            'query' => $query,
            'columns' => ['id', 'Nom', 'Type', 'Sous Type','Etablissement','Parent','Etat','Sous Lieu','Superficie (m2)','Capcite theorique','Capcite reelle','etudiants affectés'],
            'filename' => 'Lieux.xlsx',
            'map' => fn($item) => [
                'id' => $item->id,
                'Nom' => $item->libelle_fr,
                'Type' => $item->typeLieu->libelle_court_fr ?? '',
                'Sous Type' => $item->sousTypeLieu->libelle_court_fr ?? '',
                'Etablissement' => $item->etablissementLieu ? $item->etablissementLieu->denomination_fr : 'N/A',
                'Parent' => $item->parent->libelle_fr ?? '',
                'Etat' => $item->etatLieu->libelle_court_fr ?? '',
                'Sous Lieu' => $item->children_count,
                'Superficie (m2)' => $item->surface_globale ?? '',
                'Capcite theorique' => $item->capacite_theorique ?? '',
                'Capcite reelle' => $item->capacite_reelle ?? '',
                'etudiants affectés' => $item->affectation_count,
            ],
        ];
    }

    private function getEtudiantsExportInfo(): array
    {
        $query = Onou_cm_demande::query();

        return [
            'query' => $query,
            'columns' => ['id', 'nom', 'prenom'],
            'filename' => 'etudiants.xlsx',
            'map' => fn($item) => [
                'id' => $item->id,
                'nom' => $item->nom,
                'prenom' => $item->prenom,
            ],
        ];
    }

    private function getResidencesExportInfo(): array
    {
        $query = Onou_cm_etablissement::query();

        return [
            'query' => $query,
            'columns' => ['Code', 'nom de residence','Type','capacite theorique','capacite reelle'],
            'filename' => 'residences.xlsx',
            'map' => fn($item) => [
                'id' => $item->etablissement->identifiant,
                'nom de residence' => $item->denomination_fr,
                'Type' => $item->type_nc->libelle_court_fr,
                'capacite theorique' => $item->capacite_theorique ? $item->capacite_theorique:0,
                'capacite reelle' => $item->capacite_relle ? $item->capacite_relle :0,
            ],
        ];
    }
    private function getStatistiquesExportInfo(): array
    {
        $query = vm_heb_processing_by_ru::query()->with(['etablissement'])
            ->whereNotNull('residence');

        return [
            'query' => $query,
            'columns' => ['nom de residence','Capacite','Total','Pending','Accepted','Rejected','%'],
            'filename' => 'statistiques.xlsx',
            'map' => fn($item) => [
                'nom de residence' => $item->etablissement->denomination_fr,
                'Capacite' => $item->capacite ? $item->capacite:0,
                'Total' => $item->total ? $item->total:0,
                'Pending' => $item->pending ? $item->pending:0,
                'Accepted' => $item->accepted ? $item->accepted:0,
                'Rejected' => $item->rejected ?$item->rejected:0,
                'percentage' => $item->capacite > 0 ? round(($item->accepted / $item->capacite) * 100, 2) : 0,
            ],
        ];
    }

    public function render()
    {
        return view('livewire.export-data');
    }
}
