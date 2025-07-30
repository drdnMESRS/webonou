<?php

namespace App\Actions\Pages\Dossier_demande_Hebergement;

use App\Actions\Pages\Dossier_demande_Hebergement\Common\FindDemande;
use App\Models\Onou\Onou_cm_demande;
use App\Models\Cursus\Dossier_inscription_administrative;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FindDemandeById extends FindDemande
{
    public function handle(int $id): array
    {
        $this->validateId($id);

        $demande = Onou_cm_demande::fetchDemandeById($id, $this->getSelectFields());

        if (is_null($demande)) {
            throw new NotFoundHttpException('Demande not found with ID: '.$id);
        }

        $historique_translated = $this->fetchingTheHistoricalDataForTheIndividual($demande);
        $historique_dia = Dossier_inscription_administrative::fetchAllInscrptionByIdividu($demande->id_individu, $this->getSelectFieldsHisInc());
        $historique_dia_trad=$this->getinscTranslated($historique_dia);

        // Check if the demand is compliant
        (new CheckConformeHeb(collect($demande)->toArray()))->handle();

        if (! $demande) {
            throw new NotFoundHttpException('Demande not found with ID: '.$id);
        }
        return $this->mapToDTO($demande, $historique_translated, $historique_dia_trad)->toArray();

    }

    private function validateId(int $id): void
    {
        if (is_null($id) || $id <= 0) {
            throw new \InvalidArgumentException('Invalid demande ID provided');
        }
    }

    /**
     * @param $demande
     * @return mixed
     */
    public function fetchingTheHistoricalDataForTheIndividual($demande)
    {
        $historique_heb = Onou_cm_demande::fetchAllDemandeByIdividu($demande->id_individu, $this->getSelectFieldsHis());

        return $this->getTranslated($historique_heb);
    }


}
