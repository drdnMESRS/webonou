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

        $next($demande);
    }
}
