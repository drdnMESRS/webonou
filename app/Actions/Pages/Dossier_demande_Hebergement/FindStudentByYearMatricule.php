<?php

namespace App\Actions\Pages\Dossier_demande_Hebergement;

use App\Actions\Pages\Dossier_demande_Hebergement\Common\FindDemande;
use App\Models\Cursus\Dossier_inscription_administrative;
use App\Models\Onou\Onou_cm_demande;

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

        $historique_translated = $this->fetchingTheHistoricalDataForTheIndividual($student);

        $historique_dia = Dossier_inscription_administrative::fetchAllInscrptionByIdividu($student->id_individu, $this->getSelectFieldsHisInc());

        (new CheckConformeHeb(collect($student)->toArray()))->handle();

        return $this->mapToDTO($student, $historique_translated, $historique_dia, $type)->toArray();
    }

    /**
     * @param object $student
     * @return mixed
     */
    public function fetchingTheHistoricalDataForTheIndividual(object $student)
    {
        $historique_heb = Onou_cm_demande::fetchAllDemandeByIdividu($student->id_individu, $this->getSelectFieldsHis());

        return $this->getTranslated($historique_heb);
    }
}
