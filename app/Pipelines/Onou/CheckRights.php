<?php

namespace App\Pipelines\Onou;

use App\CommonClasses\Onou\Alerts;

class CheckRights extends Alerts
{
    protected ?string $title = 'Confirmité d : ';

    protected ?string $type = 'checkAge';

    public function handle(array $demande, \Closure $next)
    {

        if (! $demande['reinscription']) {
            $this->status = 'danger';
            $this->type = 'checkreinscription';
            $this->message = 'Aucune reinscription est effectué pour cette année universitaire';
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkreinscription';
            $this->message = 'Etudiant inscrie pour l\année universitaire';
            $this->flush_alert();
        }

        if (! $demande['abondan']) {
            $this->status = 'danger';
            $this->type = 'checkreabondan';
            $this->message = 'Etudiant est declaré abondant veuillez contacté l université';
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkreabondan';
            $this->message = 'Situation pidagogique réguliere ';
            $this->flush_alert();
        }

        if (! $demande['frais_hebergement']) {
            $this->status = 'danger';
            $this->type = 'checkfrais_hebergement';
            $this->message = 'Etudiant à un demande non payé l année passe il n a pas le droit a renouvler';
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkfrais_hebergement';
            $this->message = 'Situation vis a vis paiement des frais d hebergement réguliere réguliere ';
            $this->flush_alert();
        }

        if (! $demande['deuxieme_diplome']) {
            $this->status = 'danger';
            $this->type = 'checkdeuxieme_diplome';
            $this->message = 'Etudiant a déja un deuxieme diplome, il n a pas le droit a renouvler';
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkdeuxieme_diplome';
            $this->message = 'Etudiant n a pas de deuxieme diplome,';
            $this->flush_alert();
        }

        if (! $demande['retard_scolaire']) {
            $this->type = 'checkretard_scolaire';
            $this->status = 'danger';
            $this->message = 'Etudiant a un retard scholaire, il n a pas le droit a renouvler';
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkretard_scolaire';
            $this->message = 'Situation pidagogique réguliere';
            $this->flush_alert();
        }

        if (! $demande['retard_niveau']) {
            $this->type = 'checkretard_niveau';
            $this->status = 'danger';
            $this->message = 'Etudiant a un retard scholaire dan un niveau + de 2 redeblement, il n a pas le droit a renouvler';
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkretard_niveau';
            $this->message = 'Situation pidagogique réguliere';
            $this->flush_alert();
        }

        if ($demande['cles_remis']) {
            $this->type = 'checkcles_remis';
            $this->status = 'danger';
            $this->message = 'L’étudiant a une ancienne demande avec clés non remises, il n’a pas le droit de renouveler.';
            $this->flush_alert();
        } else {
            $this->status = 'success';
            $this->type = 'checkcles_remis';
            $this->message = 'Situation hebergement réguliere';
            $this->flush_alert();
        }

        if (!isset($demande['id_dia'])) {
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
