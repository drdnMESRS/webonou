<?php

namespace App\Models\Onou;

use App\Models\Cursus\Dossier_inscription_administrative;
use App\Models\doctorat\Fichier_national_doctorant;
use App\Models\doctorat\Suiv_fichier_national_doctorant;
use App\Models\Nc\Nomenclature;
use App\Models\Ppm\Ref_Individu;
use App\Models\Scopes\Dou\AcademicyearScope;
use App\Models\Scopes\Dou\DouScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use YMigVal\LaravelModelCache\HasCachedQueries;
use YMigVal\LaravelModelCache\ModelRelationships;

#[scopedBy(AcademicyearScope::class)]
#[ScopedBy(DouScope::class)]

class Onou_cm_demande extends Model
{
    use HasCachedQueries, ModelRelationships;

    /** @use HasCachedQueries<Onou_cm_demande> */

    // Optional: Override the global prefix from config('model-cache.cache_key_prefix') for this model
    protected $cachePrefix = 'Onou_cm_demande_';

    protected $table = 'onou.onou_cm_demande';

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dou',
        'residence',
        'approuvee_heb_dou',
        'date_approuve_heb_dou',
        'affectation',
        'individu',
        'id_dia',
        'id_fnd',
        'observ_heb_dou',
        'approuvee_heb_resid',
        'date_approuve_heb_resid',
        'observ_heb_resid',
        'annee_academique',
        'cles_remis',
        'cles_remis_at',
        'traiter_par_dou',
        'traiter_par_ru',
    ];

    /**
     * Relationship belongs to Ref_individu.
     */
    public function individu_detais(): BelongsTo
    {
        return $this->belongsTo(Ref_Individu::class, 'individu', 'id');
    }

    public function scopePlaceOccupe(Builder $query, int $lieuId): Builder
    {
        return $query->selectRaw('COUNT(*) as place_occupe')
            ->join('onou.onou_cm_affectation_individu as aff', 'aff.id', '=', 'onou.onou_cm_demande.affectation')
            ->join('onou.onou_cm_lieu as lieu', 'lieu.id', '=', 'aff.lieu')
            ->where('lieu.id', $lieuId);
    }

    /*
     * Relationship belongs to dossier_inscription_administrative.
     */
    public function dossier_inscription_administrative(): BelongsTo
    {
        return $this->belongsTo(Dossier_inscription_administrative::class, 'id_dia', 'id');
    }

    public function fichier_national_doctorant(): BelongsTo
    {
        return $this->belongsTo(Fichier_national_doctorant::class, 'id_fnd', 'id');
    }

    public function suiv_fichier_national_doctorant()
    {
        return $this->hasOneThrough(
            Suiv_fichier_national_doctorant::class,
            Fichier_national_doctorant::class,
            'id',           //   FichierNationalDoctorant
            'id_fnd',       //   SuiviFichierNationalDoctorant
            'id_fnd',       //   OnouCmDemande
            'id'            //   FichierNationalDoctorant
        );
    }

    /**
     * Relationship belongs to Nomenclature on commune_residence.
     */
    public function nc_commune_residence(): BelongsTo
    {
        return $this->belongsTo(Nomenclature::class, 'commune_residence', 'id');
    }

    public function residenceaffectation()
    {
        return $this->belongsTo(Onou_cm_etablissement::class, 'residence', 'id');
    }

    public function affectationlieu()
    {
        return $this->belongsTo(Onou_cm_affectation_individu::class, 'affectation', 'id');
    }

    public static function fetchDemandeById(int $id, ?array $getSelectFields = ['*'])
    {
        return cache()->remember('demande_'.$id, 60 * 60 * 12, function () use ($getSelectFields, $id) {
            return DB::table('onou.onou_cm_demande as demande')
                ->select($getSelectFields)
                ->leftJoin('ppm.ref_individu as individu', function ($join) {
                    $join->on('individu.id', '=', 'demande.individu')
                        ->where('individu.active', '=', 1);
                })
                ->leftJoin('cursus.dossier_inscription_administrative as inscription', 'inscription.id', '=', 'demande.id_dia')
                ->leftJoin('lmd.ouverture_offre_formation as ouverture', 'ouverture.id', '=', 'inscription.id_ouverture_of')
                ->leftJoin('lmd.niveau as niveau', 'niveau.id', '=', 'inscription.id_niveau')
                ->leftJoin('ppm.ref_etablissement as etablissement', 'etablissement.id', '=', 'inscription.id_etablissement')
                ->leftJoin('lmd.domaine_lmd as domaine', 'domaine.id', '=', 'inscription.id_domaine')
                ->leftJoin('lmd.filiere_lmd as filiere', 'filiere.id', '=', 'inscription.id_filiere')
                ->leftJoin('nc.nomenclature as commune', 'commune.id', '=', 'demande.commune_residence')
                ->leftJoin('lmd.offre_formation as offre', 'offre.id', '=', 'ouverture.id_offre_formation')
                ->leftJoin('lmd.cycle as cycle', 'cycle.id', '=', 'niveau.id_cycle')
                ->leftJoin('ppm.ref_structure as structure', 'structure.id', '=', 'ouverture.id_structure')
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
                ->leftJoin('onou.onou_droit_renouvellement_heb as droit_renouvellement', 'droit_renouvellement.id_individu', '=', 'demande.individu')

                ->where([
                    ['demande.id', '=', $id],
                ])
                ->leftJoin('cursus.conge_academique as cong', function ($q) {
                    $q->on('cong.id_dossier_inscription', '=', 'inscription.id')
                        ->where('cong.demande_validee', true);
                })
                ->firstOrFail();
        });
    }

    public static function fetchAllDemandeByIdividu(int $id, ?array $getSelectFields = [])
    {

        return cache()->remember('History_demande_'.$id, 60 * 60 * 12, function () use ($getSelectFields, $id) {
            return DB::table('onou.onou_cm_demande as demande')
                ->distinct()
                ->select(
                    array_merge([
                      DB::raw('CONCAT(annee.premiere_annee, \'/\', annee.deuxieme_annee) as annee_academique_libelle'),
                    ], $getSelectFields)
                )
                ->leftJoin('lmd.annee_academique as annee', 'annee.id', '=', 'demande.annee_academique')
                ->leftJoin('ppm.ref_individu as individu', function ($join) {
                    $join->on('individu.id', '=', 'demande.individu')
                        ->where('individu.active', '=', 1);
                })
                ->leftJoin('onou.onou_cm_etablissement as dou', 'dou.id', '=', 'demande.dou')
                ->leftJoin('onou.onou_cm_etablissement as residence', 'residence.id', '=', 'demande.residence')
                ->leftJoin('onou.onou_cm_affectation_individu as affectation', function (JoinClause $join) {
                    $join->on('affectation.id', '=', 'demande.affectation');
                })
                ->leftJoin('ppm.ref_compte as ref_comptedou', 'demande.traiter_par_dou', '=', 'ref_comptedou.id')
                ->leftJoin('ppm.ref_compte as ref_compteru', 'demande.traiter_par_ru', '=', 'ref_compteru.id')
                ->leftJoin('ppm.ref_individu as comptedou', 'ref_comptedou.individu', '=', 'comptedou.id')
                ->leftJoin('ppm.ref_individu as compteru', 'ref_compteru.individu', '=', 'compteru.id')
                ->leftJoin('onou.onou_cm_lieu as lieu', 'lieu.id', '=', 'affectation.lieu')
                ->where([
                    ['individu.id', '=', $id],
                    ['demande.rang', '=', 1],
                ])
                ->get();
        });
    }
}
