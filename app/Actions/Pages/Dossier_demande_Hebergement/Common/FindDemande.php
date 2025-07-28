<?php

namespace App\Actions\Pages\Dossier_demande_Hebergement\Common;

use App\DTO\Onou\DemandeHebergementDTO;
use App\Models\Onou\Onou_cm_demande;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FindDemande
{
    public function getSelectFields(): array
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
            'inscription.numero_inscription',
            'inscription.frais_inscription_paye',
            'inscription.est_transfert',
            'cong.demande_validee as conge_acad',
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

    public function mapToDTO($demande, $historique, $historique_dia = null): DemandeHebergementDTO
    {

        return (new DemandeHebergementDTO)->FromArray([
            'id' => $demande->id_demande,
            'frais_hebregement_pay' => $demande->hebergement_paye,
            'actual_page' => $this->getPageFromUrl(),
            'individu' => $this->getIndividu($demande),
            'dossierInscriptionAdministrative' => $this->getInscription($demande),
            'demandeHebergement' => [
                __('views/livewire/onou/forms/demande_details.1er_choix_arabe') => $demande->choix1_arabe ?? ' - ',
                __('views/livewire/onou/forms/demande_details.1er_choix') => $demande->choix1 ?? ' - ',
                __('views/livewire/onou/forms/demande_details.2er_choix_arabe') => $demande->choix2_arabe ?? ' - ',
                __('views/livewire/onou/forms/demande_details.2er_choix') => $demande->choix2 ?? ' - ',
                __('views/livewire/onou/forms/demande_details.3er_choix_arabe') => $demande->choix3_arabe ?? ' - ',
                __('views/livewire/onou/forms/demande_details.3er_choix') => $demande->choix3 ?? ' - ',
                __('views/livewire/onou/forms/demande_details.date_demande') => ($demande->date_demande_heb) ? Carbon::make($demande->date_demande_heb)->format('d/m/Y') : ' - ',
            ],
            'historiqueHebergement' => $historique->toArray(),
            'historiqueInscription' => $historique_dia?->toArray() ?? [],
            'adressIndividue' => $this->getadressIndividue($demande),
            'cles_remis' => ($demande->cles_remis),
            'cles_remis_at' => ($demande->cles_remis_at) ? Carbon::make($demande->cles_remis_at)->format('d/m/Y H:i') : ' - ',

        ]);
    }

    public function getIndividu($demande): array
    {
        // individu informations
        return [
            __('views/livewire/onou/forms/demande_details.nin') => $demande->identifiant,
            __('views/livewire/onou/forms/demande_details.numero_inscription') => $demande->numero_inscription,
            __('views/livewire/onou/forms/demande_details.nom') => $this->full_name($demande, ['nom_latin', 'prenom_latin']),
            __('views/livewire/onou/forms/demande_details.nom_arabe') => $this->full_name($demande, ['nom_arabe', 'prenom_arabe']),
            __('views/livewire/onou/forms/demande_details.nom_du_pere') => $this->full_name($demande, ['nom_latin', 'prenom_pere_latin']),
            __('views/livewire/onou/forms/demande_details.nom_du_pere_arabe') => $this->full_name($demande, ['nom_arabe', 'prenom_pere_arabe']),
            __('views/livewire/onou/forms/demande_details.nom_du_mere') => $this->full_name($demande, ['nom_mere_latin', 'prenom_mere_latin']),
            __('views/livewire/onou/forms/demande_details.date_naissance') => $demande->date_naissance,
            __('views/livewire/onou/forms/demande_details.lieu_naissance') => $demande->lieu_naissance,
            __('views/livewire/onou/forms/demande_details.nationalite_arabe') => $demande->nationalite_arabe ?? ' ',
            __('views/livewire/onou/forms/demande_details.nationalite') => $demande->nationalite ?? ' ',
            __('views/livewire/onou/forms/demande_details.civilite') => $demande->civilite == 1 ? __('views/livewire/onou/forms/demande_details.garcon') : __('views/livewire/onou/forms/demande_details.fille'),
            __('views/livewire/onou/forms/demande_details.rfid') => $demande->id_carde,

        ];
    }

    public function getInscription($demande): array
    {
        return [
            __('views/livewire/onou/forms/demande_details.numero_inscription') => $demande->numero_inscription,
            __('views/livewire/onou/forms/demande_details.payment_inscription') => $demande->frais_inscription_paye,
            // 'code_etablissement' => $demande->etab_identifiant,
            __('views/livewire/onou/forms/demande_details.etablissement_arabe') => $demande->etab_identifiant.' - '.$demande->ll_etablissement_arabe,
            __('views/livewire/onou/forms/demande_details.etablissement') => $demande->etab_identifiant.' - '.$demande->ll_etablissement_latin,
            // 'offre_code' => $demande->code,
            __('views/livewire/onou/forms/demande_details.offre_de_formation') => $demande->libelle_long_fr,
            __('views/livewire/onou/forms/demande_details.offre_de_formation_arabe') => $demande->of_libelle_long_ar,

            __('views/livewire/onou/forms/demande_details.niveau_arabe') => $demande->niveau_libelle_long_ar,
            __('views/livewire/onou/forms/demande_details.niveau') => $demande->niveau_libelle_long_lt,
            // 'code_domaine' => $demande->lc_domaine,
            __('views/livewire/onou/forms/demande_details.domaine_arabe') => $demande->ll_domaine_arabe,
            __('views/livewire/onou/forms/demande_details.domaine') => $demande->ll_domaine,

            __('views/livewire/onou/forms/demande_details.filiere_arabe') => $demande->ll_filiere_arabe,
            __('views/livewire/onou/forms/demande_details.filiere') => $demande->ll_filiere,
            __('views/livewire/onou/forms/demande_details.commune_arabe') => $demande->commune_libelle_long_ar ?? ' ',
            __('views/livewire/onou/forms/demande_details.commune') => $demande->commune_libelle_long_f ?? ' ',

            // 'cycle_code' => $demande->code,
            __('views/livewire/onou/forms/demande_details.cycle') => $demande->libelle_long_lt,
            __('views/livewire/onou/forms/demande_details.cycle_arabe') => $demande->libelle_long_ar,
            // 'structure_code' => $demande->strecture_code,
            __('views/livewire/onou/forms/demande_details.structure_arabe') => $demande->ll_structure_arabe,
            __('views/livewire/onou/forms/demande_details.structure') => $demande->ll_structure_latin,
            __('views/livewire/onou/forms/demande_details.est_transfert') => $demande->est_transfert,
            __('views/livewire/onou/forms/demande_details.conge_academique') => $demande->conge_acad,
        ];
    }

    public function full_name($demande, ?array $columns): string
    {
        $name = '';
        foreach ($columns as $column) {
            $name .= ' '.$demande->$column;
        }

        return $name;
    }

    public function getadressIndividue($demande): array
    {
        return [
            __('views/livewire/onou/forms/demande_details.adresse') => $demande->libelle_adresse_latin ?? ' - ',
            __('views/livewire/onou/forms/demande_details.adresse_arabe') => $demande->libelle_adresse_arabe ?? ' - ',
            __('views/livewire/onou/forms/demande_details.commune') => $demande->commune ?? ' - ',
            __('views/livewire/onou/forms/demande_details.commune_arabe') => $demande->commune_arabe ?? ' - ',
            __('views/livewire/onou/forms/demande_details.daira') => $demande->daira ?? ' - ',
            __('views/livewire/onou/forms/demande_details.daira_arabe') => $demande->daira_arabe ?? ' - ',

            __('views/livewire/onou/forms/demande_details.wilaya') => $demande->wilaya ?? ' - ',
            __('views/livewire/onou/forms/demande_details.wilaya_arabe') => $demande->wilaya_arabe ?? ' - ',

            __('views/livewire/onou/forms/demande_details.pays') => $demande->pays ?? ' - ',
            __('views/livewire/onou/forms/demande_details.pays_arabe') => $demande->pays_arabe ?? ' - ',

            __('views/livewire/onou/forms/demande_details.type_adresse') => $demande->type_adresse ?? ' - ',
            __('views/livewire/onou/forms/demande_details.type_adresse_arabe') => $demande->type_adresse_arabe ?? ' - ',
        ];
    }

    public function getSelectFieldsHis(): array
    {
        return [
            'demande.renouvellement',
            'residence.denomination_ar as residance_arabe',
            'residence.denomination_fr as residance',
            'dou.denomination_ar as dou_arabe',
            'dou.denomination_fr as dou',
            'demande.approuvee_heb_dou  as decision_du_DCC_:',
            'demande.date_approuve_heb_dou as traiter_par_DCC_le_:',
            'demande.approuvee_heb_resid as decision_du_Residence_:',
            'demande.date_approuve_heb_resid as traiter_par_Resi_le_:',
            'demande.hebergement_paye',
            'demande.hebergement_paye_date as date_de_paiment',
            'lieu.libelle_fr as chambre',
            'demande.cles_remis',
            'demande.cles_remis_at',
            \DB::raw("CONCAT(comptedou.nom_latin, ' ', comptedou.prenom_latin) as au_niveau_de_la_dou_traiter_par"),
            \DB::raw("CONCAT(compteru.nom_latin, ' ', compteru.prenom_latin) as au_niveau_de_la_ru_traiter_par"),

        ];
    }

    public function getHistoriqueHebergementLabels(): array
    {
        return [
            'renouvellement' => __('views/livewire/onou/forms/demande_details.renouvellement'),
            'residance_arabe' => __('views/livewire/onou/forms/demande_details.residance_arabe'),
            'residance' => __('views/livewire/onou/forms/demande_details.residance'),
            'dou_arabe' => __('views/livewire/onou/forms/demande_details.dou_arabe'),
            'dou' => __('views/livewire/onou/forms/demande_details.dou'),
            'decision_du_DCC' => __('views/livewire/onou/forms/demande_details.decision_du_DCC'),
            'traiter_par_DCC_le' => __('views/livewire/onou/forms/demande_details.traiter_par_DCC_le'),
            'decision_du_Residence' => __('views/livewire/onou/forms/demande_details.decision_du_Residence'),
            'traiter_par_Resi_le' => __('views/livewire/onou/forms/demande_details.traiter_par_Resi_le'),
            'hebergement_paye' => __('views/livewire/onou/forms/demande_details.payment_hebergement'),
            'date_de_paiment' => __('views/livewire/onou/forms/demande_details.date_de_paiment'),
            'chambre' => __('views/livewire/onou/forms/demande_details.chambre'),
            'cles_remis' => __('views/livewire/onou/forms/demande_details.cle_remise'),
            'cles_remis_at' => __('views/livewire/onou/forms/demande_details.cles_remis_at'),
            'au_niveau_de_la_dou_traiter_par' => __('views/livewire/onou/forms/demande_details.traiter_par_dou'),
            'au_niveau_de_la_ru_traiter_par' => __('views/livewire/onou/forms/demande_details.traiter_par_resid'),
        ];
    }

    public function getSelectFieldsHisInc(): array
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
            'cong.demande_validee as conge_acad',
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


    /**
     * @param $historique_heb
     * @return mixed
     */
    public function getTranslated($historique_heb)
    {
        //TODO verify the translation method
        return $historique_heb;

        $historique_translated = $historique_heb->map(function ($row) {
            $labels = $this->getHistoriqueHebergementLabels();
            $entry = [];

            foreach ($labels as $key => $label) {
                $entry[$label] = $row->$key ?? null;
            }

            return $entry;
        });
        return $historique_translated;
    }
}
