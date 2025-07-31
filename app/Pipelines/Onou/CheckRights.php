<?php

namespace App\Pipelines\Onou;

use App\CommonClasses\Onou\Alerts;

class CheckRights extends Alerts
{
    protected ?string $title = null;

    protected ?string $type = 'checkAge';
    public function __construct()
    {
        $this->title = __('pipelines/onou/alerts.confirmite') . ' : ';
    }
    public function handle(array $demande, \Closure $next)
    {

        if (! $demande['reinscription']) {
            $this->status = 'danger';
            $this->type = 'checkreinscription';
            $this->message = __('pipelines/onou/alerts.etudiant_not_inscrie');
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkreinscription';
            $this->message =__('pipelines/onou/alerts.etudiant_inscrie');
            $this->flush_alert();
        }

   if (!$demande['frais_inscription_paye']) {
            $this->status = 'danger';
            $this->type = 'frais_inscription_paye';
            $this->message = __('pipelines/onou/alerts.frais_inscription_non_paye');
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'frais_inscription_paye';
            $this->message = __('pipelines/onou/alerts.frais_inscription_paye');
            $this->flush_alert();
        }

        if (! $demande['abondan']) {
            $this->status = 'danger';
            $this->type = 'checkreabondan';
            $this->message = __('pipelines/onou/alerts.abondant');
            $this->flush_alert();
        }

        if (! $demande['frais_hebergement']) {
            $this->status = 'danger';
            $this->type = 'checkfrais_hebergement';
            $this->message = __('pipelines/onou/alerts.paiement_hebergement_not_reguliere');
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkfrais_hebergement';
            $this->message = __('pipelines/onou/alerts.paiement_hebergement_reguliere');
            $this->flush_alert();
        }

        if (! $demande['deuxieme_diplome']) {
            $this->status = 'danger';
            $this->type = 'checkdeuxieme_diplome';
            $this->message =__('pipelines/onou/alerts.etudiant_a_deuxieme_diplome');
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkdeuxieme_diplome';
            $this->message = __('pipelines/onou/alerts.etudiant_pas_deuxieme_diplome');
            $this->flush_alert();
        }

        if (! $demande['retard_scolaire']) {
            $this->type = 'checkretard_scolaire';
            $this->status = 'danger';
            $this->message = __('pipelines/onou/alerts.etudiant_a_retard_scholaire');
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkretard_scolaire';
            $this->message = __('pipelines/onou/alerts.etudiant_pas_retard_scholaire');
            $this->flush_alert();
        }

        if (! $demande['retard_niveau']) {
            $this->type = 'checkretard_niveau';
            $this->status = 'danger';
            $this->message = __('pipelines/onou/alerts.etudiant_a_retard_scholaire_niveau');
            $this->flush_alert();
        }

        if ($demande['cles_remis']) {
            $this->type = 'checkcles_remis';
            $this->status = 'danger';
            $this->message = __('pipelines/onou/alerts.cle_remise');
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkcles_remis';
            $this->message =  __('pipelines/onou/alerts.cle_not_remise');
            $this->flush_alert();
        }
//dd($demande);
        if (isset($demande['id_fnd'])) {
            if (isset($demande['id_suivi_fnd'])) {
                $this->status = 'success';
                $this->type = 'checkreinscription_doctort';
                $this->message = 'Etudiant inscrie pour l\année universitaire';
                $this->flush_alert();
            } else {
                $this->status = 'danger';
                $this->type = 'checkreinscription_doctort';
                $this->message = 'Aucune reinscription doctorat est effectué pour cette année universitaire';
                $this->flush_alert();
            }
        }

        $existing = session('checks', []);
        $existing[$this->type] = [
            'status' => $this->status,
            'message' => $this->message,
            'title' => $this->title,
        ];
        session(['checks' => $existing]);

        $next($demande);
    }
}
