<?php

namespace App\Models\doctorat;

use App\Models\Lmd\Domain_lmd;
use App\Models\Lmd\Filiere_lmd;
use App\Models\Lmd\Niveau;
use App\Models\Lmd\Specialite_lmd;
use App\Models\Nc\Nomenclature;
use App\Models\Ppm\Ref_etablissement;
use App\Models\Ppm\Ref_structure;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Fichier_national_doctorant extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'doctorat.fichier_national_doctorant';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Ref_etablissement::class, 'id_etablissement', 'id');
    }

    public function domaine(): BelongsTo
    {
        return $this->belongsTo(Domain_lmd::class, 'id_domaine', 'id');
    }

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere_lmd::class, 'id_filiere', 'id');
    }

    public function specialite(): BelongsTo
    {
        return $this->belongsTo(Specialite_lmd::class, 'id_specialite', 'id');
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Ref_structure::class, 'id_id_structure', 'id');
    }

    public function suiv_fichier_national_doctorant(): HasOne
    {
        return $this->hasOne(fichier_national_doctorant::class, 'id_fnd', 'id');
    }

    public function sexe(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'id_nc_sexe', 'id');
    }

    public static function fetchAllInscrptionByIdividu(int $id, ?array $getSelectFields = ['*'])
    {
        // return cache()->remember('demande_' . $anne_bac . '_' . $matricule, 60 * 60 * 12, function () use ($getSelectFields, $anne_bac, $matricule) {
        return DB::table('cursus.dossier_etudiant as etudiant')
            ->select($getSelectFields)
            ->leftJoin('bac.dossier_bachelier as bachelier', 'bachelier.id', '=', 'etudiant.id_dossier_bachelier')
            ->leftJoin('cursus.dossier_inscription_administrative as inscription', 'inscription.id_dossier_etudiant', '=', 'etudiant.id')
            ->leftJoin('cursus.dossier as dossier', 'dossier.id', '=', 'inscription.id')
            ->leftJoin('lmd.annee_academique as anneeacd', 'anneeacd.id', '=', 'inscription.id_annee_academique')
            ->leftJoin('bpm.situation_entite as situation_entite', 'dossier.id_situation', '=', 'situation_entite.id')
            ->leftJoin('bpm.situation as situation', 'situation_entite.id_situation', '=', 'situation.id')
            ->leftJoin('lmd.ouverture_offre_formation as ouverture', 'ouverture.id', '=', 'inscription.id_ouverture_of')
            ->leftJoin('lmd.niveau as niveau', 'niveau.id', '=', 'inscription.id_niveau')
            ->leftJoin('lmd.domaine_lmd as domaine', 'domaine.id', '=', 'inscription.id_domaine')
            ->leftJoin('lmd.filiere_lmd as filiere', 'filiere.id', '=', 'inscription.id_filiere')
            ->leftJoin('ppm.ref_etablissement as etablissement', 'etablissement.id', '=', 'inscription.id_etablissement')
            // ->leftJoin('onou.onou_cm_demande as demande', 'demande.id_dia', '=', 'inscription.id')
            // ->leftJoin('nc.nomenclature as commune', 'commune.id', '=', 'demande.commune_residence')
            ->leftJoin('fve_examen.bilan_session as bilan', function (JoinClause $join) {
                $join->on('bilan.id_dossier_inscription', '=', 'inscription.id')
                    ->where([
                        ['bilan.annuel', '=', true],
                    ]);
            })
            ->leftJoin('nc.nomenclature as decision', 'decision.id', '=', 'bilan.nc_decision')
            ->leftJoin('lmd.offre_formation as offre', 'offre.id', '=', 'ouverture.id_offre_formation')
            ->leftJoin('lmd.cycle as cycle', 'cycle.id', '=', 'niveau.id_cycle')
            ->leftJoin('ppm.ref_structure as structure', 'structure.id', '=', 'ouverture.id_structure')
            // ->leftJoin('nc.nomenclature AS nationalite', 'nationalite.id', '=', 'individu.nationalite')
            // ->leftJoin('ppm.ref_coordonnee as coordonnee', function (JoinClause $join) {
            //     $join->on('coordonnee.individu', '=', 'individu.id')
            //         ->where([
            //             ['coordonnee.type_coordonnee', '=', 1],
            //         ]);
            // })

            // ->leftJoin('ppm.ref_adresse as adress', 'adress.id', '=', 'coordonnee.id')
            // ->leftJoin('nc.nomenclature as adress_commune', 'adress.commune', '=', 'adress_commune.id')
            // ->leftJoin('nc.nomenclature as adress_daira', 'adress.daira', '=', 'adress_daira.id')
            // ->leftJoin('nc.nomenclature as adress_wilaya', 'adress.wilaya', '=', 'adress_wilaya.id')
            // ->leftJoin('nc.nomenclature as adress_pays', 'adress.pays', '=', 'adress_pays.id')
            // ->leftJoin('nc.nomenclature as adress_type_adresse', 'adress.type_adresse', '=', 'adress_type_adresse.id')
            // ->leftJoin('onou.onou_cm_etablissement as choix1', 'choix1.id', '=', 'demande.choix1')
            // ->leftJoin('onou.onou_cm_etablissement as choix2', 'choix2.id', '=', 'demande.choix2')
            // ->leftJoin('onou.onou_cm_etablissement as choix3', 'choix3.id', '=', 'demande.choix3')
            // ->leftJoin('onou.onou_droit_renouvellement_heb as droit_renouvellement', 'droit_renouvellement.id_individu', '=', 'etudiant.id_individu')

            ->where([
                ['etudiant.id_individu', '=', $id],
                // ['individu.active', '=', 1],
            ])
            ->orderby('anneeacd.id')->get();

        //  });
    }

    public static function FindByYearMatriculePostGraduation(string $anne_bac, string $matricule, ?array $getSelectFields = ['*'])
    {
        // return cache()->remember('demande_' . $anne_bac . '_' . $matricule, 60 * 60 * 12, function () use ($getSelectFields, $anne_bac, $matricule) {
        return DB::table('doctorat.fichier_national_doctorant as doctora')
            ->select($getSelectFields)
            // ->join('cursus.dossier_etudiant as etudiant', 'etudiant.id_dossier_bachelier', 'bachelier.id')
            ->leftJoin('ppm.ref_individu as individu', 'individu.id', '=', 'doctora.id_individu')
            ->leftJoin('ppm.ref_etablissement as etablissement', 'etablissement.id', '=', 'doctora.id_etablissement')
            ->leftJoin('lmd.domaine_lmd as domaine', 'domaine.id', '=', 'doctora.id_domaine')
            ->leftJoin('lmd.filiere_lmd as filiere', 'filiere.id', '=', 'doctora.id_filiere')
            ->leftJoin('ppm.ref_structure as structure', 'structure.id', '=', 'doctora.id_structure')
            ->leftJoin('lmd.specialite_lmd as specialite', 'specialite.id', '=', 'doctora.id_specialite')

            ->leftJoin('doctorat.suivi_fichier_national_doctorant as suivdoctora', function ($join) {
                $join->on('suivdoctora.id_fnd', '=', 'doctora.id')
                    ->where('suivdoctora.id_annee_academique', '=', (new \App\Actions\Sessions\AcademicYearSession)->get_academic_year());
            })

            ->leftJoin('nc.nomenclature as situation', 'situation.id', '=', 'suivdoctora.id_situation')
            ->leftJoin('cursus.paiement_frais_inscription as paiement', function ($join) {
                $join->on('paiement.id_suivi_doctorat', '=', 'suivdoctora.id')
                    ->where([
                        ['paiement.id_annee_academique', '=', (new \App\Actions\Sessions\AcademicYearSession)->get_academic_year()],
                        ['paiement.est_paye', '=', true],
                    ]);
            })
            ->leftJoin('onou.onou_cm_demande as demande', function ($join) {
                $join->on('demande.id_fnd', '=', 'doctora.id')
                    ->where('demande.annee_academique', '=', (new \App\Actions\Sessions\AcademicYearSession)->get_academic_year());
            })
            ->leftJoin('nc.nomenclature as commune', 'commune.id', '=', 'demande.commune_residence')
            // ->leftJoin('lmd.cycle as cycle', 'cycle.id', '=', 'niveau.id_cycle')

            ->leftJoin('nc.nomenclature AS nationalite', 'nationalite.id', '=', 'individu.nationalite')
            ->leftJoin('ppm.ref_coordonnee as coordonnee', function (JoinClause $join) {
                $join->on('coordonnee.individu', '=', 'individu.id')
                    ->where([
                        ['coordonnee.type_coordonnee', '=', 1],
                    ]);
            })

            ->leftJoin('ppm.ref_adresse as adress', 'adress.id', '=', 'coordonnee.id')
            ->leftJoin('nc.nomenclature as adress_commune', 'adress.commune', '=', 'adress_commune.id')
            ->leftJoin('nc.nomenclature as adress_daira', 'adress.daira', '=', 'adress_daira.id')
            ->leftJoin('nc.nomenclature as adress_wilaya', 'adress.wilaya', '=', 'adress_wilaya.id')
            ->leftJoin('nc.nomenclature as adress_pays', 'adress.pays', '=', 'adress_pays.id')
            ->leftJoin('nc.nomenclature as adress_type_adresse', 'adress.type_adresse', '=', 'adress_type_adresse.id')
            ->leftJoin('onou.onou_cm_etablissement as choix1', 'choix1.id', '=', 'demande.choix1')
            ->leftJoin('onou.onou_cm_etablissement as choix2', 'choix2.id', '=', 'demande.choix2')
            ->leftJoin('onou.onou_cm_etablissement as choix3', 'choix3.id', '=', 'demande.choix3')
            ->leftJoin('onou.onou_droit_renouvellement_heb as droit_renouvellement', 'droit_renouvellement.id_individu', '=', 'doctora.id_individu')

            ->where([
                ['doctora.annee_bac', '=', $anne_bac],
                ['doctora.matricule_bac', '=', $matricule],
            ])
            ->first();

        //  });
    }
}
