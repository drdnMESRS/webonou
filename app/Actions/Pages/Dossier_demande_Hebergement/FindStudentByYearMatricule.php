<?php

namespace App\Actions\Pages\Dossier_demande_Hebergement;

use App\DTO\Onou\DemandeHebergementDTO;
use App\Models\Cursus\Dossier_inscription_administrative;
use App\Models\Onou\Onou_cm_demande;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FindStudentByYearMatricule
{
    public function handle(string $annee_bac, string $matricule): array
    {

        //  $this->validateYearMAtricule($annee_bac,$matricule);

        $student = Dossier_inscription_administrative::fetchDemandeByYearMatricule($annee_bac, $matricule, $this->getSelectFields());

        if (is_null($student)) {
            throw new \Exception('Student not found with Year and Matricule: '.$annee_bac.' '.$matricule);
            
        }
        // Fetching the historical data for the individual

        $historique_heb = Onou_cm_demande::fetchAllDemandeByIdividu($student->id_individu, $this->getSelectFieldsHis());
        $historique_dia = Dossier_inscription_administrative::fetchAllInscrptionByIdividu($student->id_individu, $this->getSelectFieldsHisInc());
        $result = (new CheckConformeHeb(collect($student)->toArray()))->handle();

        return $this->mapToDTO($student, $historique_heb, $historique_dia)->toArray();
    }

    // private function validateYearMAtricule(string $anne_bac,string $matricule): void
    // {
    //     if (is_null($anne_bac) || id$id <= 0) {
    //         throw new \InvalidArgumentException('Invalid demande ID provided');
    //     }
    // }

    private function getSelectFields(): array
    {
        return [
            'demande.id as id_demande',
            'demande.*',
            'individu.id as id_individu',
            'individu.identifiant',
            'individu.date_naissance',
            'individu.nom_arabe',
            'individu.nom_jeune_fille_arabe',
            'individu.nom_jeune_fille_latin',
            'individu.nom_latin',
            'individu.nom_mere_arabe',
            'individu.nom_mere_latin',
            'individu.prenom_arabe',
            'individu.prenom_latin',
            'individu.prenom_mere_arabe',
            'individu.prenom_mere_latin',
            'individu.prenom_pere_arabe',
            'individu.prenom_pere_latin',
            'individu.presume',
            'individu.civilite',
            'individu.groupe_sanguin',
            'individu.nationalite',
            'individu.situation_familiale',
            'individu.situation_service_national',
            'individu.type_individu',
            'individu.qualite',
            'individu.id',
            'individu.lieu_naissance',
            'individu.est_migree',
            'individu.active',
            'individu.etablissement',
            'individu.lieu_naissance_arabe',
            'individu.photo',
            'individu.nc_wilaya_naissance',
            'individu.date_deces',
            'individu.email',
            'individu.id_carde',
            'individu.nc_commune_naissance',
            'etablissement.identifiant as etab_identifiant',
            'etablissement.ll_etablissement_arabe',
            'etablissement.ll_etablissement_latin',
            'inscription.id as id_dia',
            'inscription.numero_inscription',
            'inscription.frais_inscription_paye',
            'inscription.est_transfert',
            'niveau.libelle_long_lt as niveau_libelle_long_lt',
            'niveau.libelle_long_ar as niveau_libelle_long_ar',
            'domaine.ll_domaine_arabe',
            'domaine.ll_domaine',
            'domaine.lc_domaine',
            'filiere.ll_filiere_arabe',
            'filiere.ll_filiere',
            'filiere.ll_filiere',
            'commune.libelle_long_ar as commune_libelle_long_ar',
            'commune.libelle_long_f as commune_libelle_long_f',
            'offre.code',
            'offre.libelle_long_fr',
            'offre.libelle_long_ar as of_libelle_long_ar',
            'cycle.code',
            'cycle.libelle_long_lt',
            'cycle.libelle_long_ar',
            'structure.identifiant as strecture_code',
            'structure.ll_structure_arabe',
            'structure.ll_structure_latin',
            'nationalite.libelle_long_ar as nationalite_arabe',
            'nationalite.libelle_long_f as nationalite',

            'adress.libelle_adresse_arabe',
            'adress.libelle_adresse_latin',

            'adress_commune.id as commune_residence_id',
            'adress_commune.libelle_long_ar as commune_arabe',
            'adress_commune.libelle_long_f as commune',

            'adress_daira.libelle_long_ar as daira_arabe',
            'adress_daira.libelle_long_f as daira',

            'adress_wilaya.libelle_long_ar as wilaya_arabe',
            'adress_wilaya.libelle_long_f as wilaya',

            'adress_pays.libelle_long_ar as pays_arabe',
            'adress_pays.libelle_long_f as pays',

            'adress_type_adresse.libelle_long_ar as type_adresse_arabe',
            'adress_type_adresse.libelle_long_f as type_adresse',

            'choix1.denomination_ar as choix1_arabe',
            'choix1.denomination_fr as choix1',

            'choix2.denomination_ar as choix2_arabe',
            'choix2.denomination_fr as choix2',

            'choix3.denomination_ar as choix3_arabe',
            'choix3.denomination_fr as choix3',

            'droit_renouvellement.reinscription as reinscription',
            'droit_renouvellement.abondan as abondan',
            'droit_renouvellement.frais_hebergement as frais_hebergement',
            'droit_renouvellement.deuxieme_diplome as deuxieme_diplome',
            'droit_renouvellement.retard_scolaire as retard_scolaire',
            'droit_renouvellement.retard_niveau as retard_niveau',

        ];
    }

    private function mapToDTO($demande, $historique, $historique_dia): DemandeHebergementDTO
    {

        return (new DemandeHebergementDTO)->FromArray([
            'id' => $demande->id_demande,
            'actual_page' => $this->getPageFromUrl(),
            'individu' => $this->getIndividu($demande),
            'dossierInscriptionAdministrative' => $this->getInscription($demande),
            'demandeHebergement' => [
                '1er_choix_arabe' => $demande->choix1_arabe ?? ' - ',
                '1er_choix' => $demande->choix1 ?? ' - ',
                '2er_choix_arabe' => $demande->choix2_arabe ?? ' - ',
                '2er_choix' => $demande->choix2 ?? ' - ',
                '3er_choix_arabe' => $demande->choix3_arabe ?? ' - ',
                '3er_choix' => $demande->choix3 ?? ' - ',
                'date_demande' => ($demande->date_demande_heb) ? Carbon::make($demande->date_demande_heb)->format('d/m/Y') : ' - ',
            ],
            'historiqueHebergement' => $historique->toArray(),
            'historiqueInscription' => $historique_dia->toArray(),
            'adressIndividue' => $this->getadressIndividue($demande),
            'id_dia' => $demande->id_dia,
            'id_individu' => $demande->id_individu,
            'cles_remis' => ($demande->cles_remis) ?? null,
            'cles_remis_at' => ($demande->cles_remis_at) ? Carbon::make($demande->cles_remis_at)->format('d/m/Y H:i') : ' - ',

        ]);
    }

    private function getIndividu($demande): array
    {
        // individu informations
        return [
            'NIN' => $demande->identifiant,
            'numero_inscription' => $demande->numero_inscription,
            'nom' => $this->full_name($demande, ['nom_latin', 'prenom_latin']),
            'nom_arabe' => $this->full_name($demande, ['nom_arabe', 'prenom_arabe']),
            'nom_du_pere' => $this->full_name($demande, ['nom_latin', 'prenom_pere_latin']),
            'nom_du_pere_arabe' => $this->full_name($demande, ['nom_arabe', 'prenom_pere_arabe']),
            'nom_du_mere' => $this->full_name($demande, ['nom_mere_latin', 'prenom_mere_latin']),
            'date_naissance' => $demande->date_naissance,
            'lieu_naissance' => $demande->lieu_naissance,
            'nationalite_arabe' => $demande->nationalite_arabe ?? ' ',
            'nationalite' => $demande->nationalite ?? ' ',
            'civilite' => $demande->civilite,

        ];
    }

    private function getInscription($demande): array
    {
        return [
            'numero_inscription' => $demande->numero_inscription,
            'frais_inscription_paye' => $demande->frais_inscription_paye,
            // 'code_etablissement' => $demande->etab_identifiant,
            'etablissement_arabe' => $demande->etab_identifiant.' - '.$demande->ll_etablissement_arabe,
            'etablissement' => $demande->etab_identifiant.' - '.$demande->ll_etablissement_latin,
            // 'offre_code' => $demande->code,
            'offre_de_formation' => $demande->libelle_long_fr,
            'offre_de_formation_arabe' => $demande->of_libelle_long_ar,

            'niveau_arabe' => $demande->niveau_libelle_long_ar,
            'niveau' => $demande->niveau_libelle_long_lt,
            // 'code_domaine' => $demande->lc_domaine,
            'domaine_arabe' => $demande->ll_domaine_arabe,
            'domaine' => $demande->ll_domaine,

            'filiere_arabe' => $demande->ll_filiere_arabe,
            'filiere' => $demande->ll_filiere,
            'commune_arabe' => $demande->commune_libelle_long_ar ?? ' ',
            'commune' => $demande->commune_libelle_long_f ?? ' ',

            // 'cycle_code' => $demande->code,
            'cycle' => $demande->libelle_long_lt,
            'cycle_arabe' => $demande->libelle_long_ar,
            // 'structure_code' => $demande->strecture_code,
            'structure_arabe' => $demande->ll_structure_arabe,
            'structure' => $demande->ll_structure_latin,
            'est_transfert' => $demande->est_transfert,
        ];
    }

    private function full_name($demande, ?array $columns): string
    {
        $name = '';
        foreach ($columns as $column) {
            $name .= ' '.$demande->$column;
        }

        return $name;
    }

    private function getadressIndividue($demande): array
    {
        return [
            'adresse' => $demande->libelle_adresse_latin ?? ' - ',
            'adresse_arabe' => $demande->libelle_adresse_arabe ?? ' - ',
            'commune' => $demande->commune ?? ' - ',
            'commune_arabe' => $demande->commune_arabe ?? ' - ',
            'daira' => $demande->daira ?? ' - ',
            'daira_arabe' => $demande->daira_arabe ?? ' - ',

            'wilaya' => $demande->wilaya ?? ' - ',
            'wilaya_arabe' => $demande->wilaya_arabe ?? ' - ',

            'pays' => $demande->pays ?? ' - ',
            'pays_arabe' => $demande->pays_arabe ?? ' - ',

            'type_adresse' => $demande->type_adresse ?? ' - ',
            'type_adresse_arabe' => $demande->type_adresse_arabe ?? ' - ',
        ];
    }

    private function getSelectFieldsHis(): array
    {
        return [
            // 'demande.renouvellement',
            'demande.hebergement_paye',
            'dou.denomination_ar as dou_arabe',
            'dou.denomination_fr as dou',
            'residence.denomination_ar as residance_arabe',
            'residence.denomination_fr as residance',
            'demande.approuvee_heb_dou  as decision_du_DCC_:',
            'demande.date_approuve_heb_dou as traiter_par_DCC_le_:',
            'demande.approuvee_heb_resid as decision_du_Residence_:',
            'demande.date_approuve_heb_resid as traiter_par_Resi_le_:',
            'demande.hebergement_paye',
            'lieu.libelle_fr as chambre',
            'demande.hebergement_paye_date as date_de_paiment',
            'demande.cles_remis',
            'demande.cles_remis_at',

        ];
    }

    private function getSelectFieldsHisInc(): array
    {
        return [
            'inscription.numero_inscription as numero_inscription',
            DB::raw('concat(bachelier.annee_bac,\' \',bachelier.matricule) as bac '),
            'situation.libelle_fr as situation_inscription',
            DB::raw('concat(anneeacd.premiere_annee,\'/\',anneeacd.deuxieme_annee) as année_académique '),
            'etablissement.ll_etablissement_arabe as etablissement_arabe ',
            'etablissement.ll_etablissement_latin as etablissement ',
            'structure.ll_structure_arabe  as structure_arabe ',
            'structure.ll_structure_latin as structure',
            // 'CONCAT (etablissement.identifiant ,etablissement.ll_etablissement_arabe) as etablissement_arabe ',
            // 'CONCAT (etablissement.identifiant,etablissement.ll_etablissement_latin) as etablissement ',
            'niveau.libelle_long_ar as niveau_arabe',
            'niveau.libelle_long_lt as niveau',
            'offre.libelle_long_ar as offre_de_formation_arabe',
            'offre.libelle_long_fr as  offre_de_formation',

            // 'domaine.ll_domaine_arabe as domaine_arabe',
            // 'domaine.ll_domaine as domaine',
            // 'filiere.ll_filiere_arabe as filiere_arabe',
            // 'filiere.ll_filiere as filiere',
            // // 'commune.libelle_long_ar as commune_arabe',
            // // 'commune.libelle_long_f as commune',
            // 'cycle.libelle_long_ar as cycle_arabe',
            // 'cycle.libelle_long_lt as cycle',
            DB::raw('concat(decision.libelle_long_ar,\'(\',bilan.moyenne,\')\') as résultat_arabe '),
            DB::raw('concat(decision.libelle_long_f,\'(\',bilan.moyenne,\')\') as résultat '),

            'inscription.est_transfert as est_transfert ',
            'inscription.frais_inscription_paye as frais_inscription_paye',

        ];
    }

    /**
     * @param  $queryParams
     * @return mixed
     */
    public function getPageFromUrl(): int
    {
        $urm = parse_url(url()->previous());
        parse_str($urm['query'] ?? '', $queryParams);
        if (isset($queryParams['page']) && is_numeric($queryParams['page'])) {
            return (int) $queryParams['page'];
        }

        return 1;
    }
}
