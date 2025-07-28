<?php

namespace App\Actions\Pages\Dossier_demande_Hebergement;

use App\DTO\Onou\DemandeHebergementDTO;
use App\Models\Cursus\Dossier_inscription_administrative;
use App\Models\Onou\Onou_cm_demande;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Actions\Pages\Dossier_demande_Hebergement\Common\FindDemande;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class FindStudentByYearMatricule extends FindDemande
{
    public function handle(string $annee_bac, string $matricule, int $type = 1): array
    {

        //  $this->validateYearMAtricule($annee_bac,$matricule);
        $student = null;

        if ($type === 1) {
            $student = Dossier_inscription_administrative::fetchDemandeByYearMatricule($annee_bac, $matricule, $this->getSelectFields());
        } else {
            $student = Dossier_inscription_administrative::FindByYearMatriculePostGraduation($annee_bac, $matricule, $this->getSelectFieldsDoctora());
        }

        if (is_null($student)) {
            throw new \Exception('Student not found with Year and Matricule: '.$annee_bac.' '.$matricule);
        }

        // Fetching the historical data for the individual

        $historique_heb = Onou_cm_demande::fetchAllDemandeByIdividu($student->id_individu, $this->getSelectFieldsHis());

        $historique_translated = $historique_heb->map(function ($row) {
             $labels = $this->getHistoriqueHebergementLabels();
            $entry = [];

            foreach ($labels as $key => $label) {
            $entry[$label] = $row->$key ?? null;
            }

            return $entry;
        });

        $historique_dia = Dossier_inscription_administrative::fetchAllInscrptionByIdividu($student->id_individu, $this->getSelectFieldsHisInc());

        $result = (new CheckConformeHeb(collect($student)->toArray()))->handle();

        return $this->mapToDTO($student, $historique_heb, $historique_dia, $type)->toArray();
    }




}
