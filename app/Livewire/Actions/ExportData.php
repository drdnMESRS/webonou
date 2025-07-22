<?php

namespace App\Livewire\Actions;

use App\Exports\GenericExport;
use App\Models\Onou\Onou_cm_demande;
use App\Models\Onou\Onou_cm_etablissement;
use App\Models\Onou\Onou_cm_lieu;
use App\Models\Onou\vm_heb_processing_by_ru;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ExportData extends Component
{
    public string $table;

    public array $specialTypes = [
        'pavilion' => 699076,
        'chambre' => 699077,
        'unite' => 699305,
    ];

    public function exportExcel()
    {
        try {
            $info = $this->getModelInfo($this->table);

            return Excel::download(
                new GenericExport($info['query'], $info['columns'], $info['map']),
                $info['filename']
            );
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur : '.$e->getMessage());
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
            ->whereIn('type_lieu', $this->specialTypes);

        return [
            'query' => $query,
            'columns' => ['id', 'Nom', 'Type', 'Sous Type', 'Etablissement', 'Parent', 'Etat', 'Sous Lieu', 'Superficie (m2)', 'Capcite theorique', 'Capcite reelle', 'etudiants affectés'],
            'filename' => 'Lieux.xlsx',
            'map' => [$this, 'mapLieux'],
        ];
    }

    public function mapLieux($item): array
    {
        return [
            'id' => $item->id,
            'Nom' => $item->libelle_fr,
            'Type' => $item->typeLieu->libelle_court_fr ?? '',
            'Sous Type' => $item->sousTypeLieu->libelle_court_fr ?? '',
            'Etablissement' => $item->etablissementLieu?->denomination_fr ?? 'N/A',
            'Parent' => $item->parent?->libelle_fr ?? '',
            'Etat' => $item->etatLieu->libelle_court_fr ?? '',
            'Sous Lieu' => $item->children_count,
            'Superficie (m2)' => $item->surface_globale ?? '',
            'Capcite theorique' => $item->capacite_theorique ?? '',
            'Capcite reelle' => $item->capacite_reelle ?? '',
            'etudiants affectés' => $item->affectation_count,
        ];
    }

    private function getEtudiantsExportInfo(): array
    {
        $query = Onou_cm_demande::query()
            ->with([
                'individu_detais',
                'residenceaffectation',
                'affectationlieu.lieuaffectation.parent',
                'nc_commune_residence',
                'dossier_inscription_administrative.etablissement',
                'dossier_inscription_administrative.domaine',
                'dossier_inscription_administrative.filiere',
                'dossier_inscription_administrative.niveau',
            ]);

        return [
            'query' => $query,
            'columns' => [
                'NIN', 'Nom', 'Sexe', 'Résidence', 'Pavillon', 'Chambre',
                'Commune', 'Etablissement', 'Domaine', 'Filière', 'Niveau',
                'Frais Inscription Payé', 'Paiement Hébergement',
            ],
            'filename' => 'Etudiants.xlsx',
            'map' => [$this, 'mapEtudiants'],
        ];
    }

    public function mapEtudiants($item): array
    {
        return [
            'NIN' => $item->individu_detais->identifiant ?? '',
            'Nom' => $item->individu_detais->full_name ?? '',
            'Sexe' => $item->individu_detais->civilite === 1 ? 'Garçon' : 'Fille',
            'Résidence' => $item->residenceaffectation->denomination_fr ?? '',
            'Pavillon' => $item->affectationlieu->lieuaffectation->parent->libelle_fr ?? '',
            'Chambre' => $item->affectationlieu->lieuaffectation->libelle_fr ?? '',
            'Commune' => $item->nc_commune_residence->full_name ?? '',
            'Etablissement' => $item->dossier_inscription_administrative->etablissement->full_name ?? '',
            'Domaine' => $item->dossier_inscription_administrative->domaine->full_name ?? '',
            'Filière' => $item->dossier_inscription_administrative->filiere->full_name ?? '',
            'Niveau' => $item->dossier_inscription_administrative->niveau->full_name ?? '',
            'Frais Inscription Payé' => $item->dossier_inscription_administrative->frais_inscription_paye ? 'Oui' : 'Non',
            'Paiement Hébergement' => $item->hebergement_paye ? 'Oui' : 'Non',
        ];
    }

    private function getResidencesExportInfo(): array
    {
        $query = Onou_cm_etablissement::query()->with(['etablissement', 'type_nc']);

        return [
            'query' => $query,
            'columns' => ['Code', 'nom de residence', 'Type', 'capacite theorique', 'capacite reelle'],
            'filename' => 'Residences.xlsx',
            'map' => [$this, 'mapResidences'],
        ];
    }

    public function mapResidences($item): array
    {
        return [
            'Code' => $item->etablissement->identifiant ?? '',
            'nom de residence' => $item->denomination_fr ?? '',
            'Type' => $item->type_nc->libelle_court_fr ?? '',
            'capacite theorique' => $item->capacite_theorique ?? 0,
            'capacite reelle' => $item->capacite_relle ?? 0,
        ];
    }

    private function getStatistiquesExportInfo(): array
    {
        $query = vm_heb_processing_by_ru::query()
            ->with(['etablissement'])
            ->whereNotNull('residence');
        dd($query);

        return [
            'query' => $query,
            'columns' => ['nom de residence', 'Capacite', 'Total', 'Pending', 'Accepted', 'Rejected', 'percentage'],
            'filename' => 'Statistiques.xlsx',
            'map' => [$this, 'mapStatistiques'],
        ];
    }

    public function mapStatistiques($item): array
    {
        dd($item);

        return [
            'nom de residence' => $item->etablissement->denomination_fr ?? '',
            'Capacite' => $item->capacite ?? 0,
            'Total' => $item->total ?? 0,
            'Pending' => $item->pending ?? 0,
            'Accepted' => $item->accepted ?? 0,
            'Rejected' => $item->rejected ?? 0,
            'percentage' => ($item->capacite ?? 0) > 0
                ? round(($item->accepted / $item->capacite) * 100, 2)
                : 0,
        ];
    }

    public function render()
    {
        return view('livewire.export-data');
    }
}
