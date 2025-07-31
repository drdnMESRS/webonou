<?php

namespace App\Pipelines\Onou;

use App\CommonClasses\Onou\Alerts;
use App\Models\Onou\Onou_heb_commune_refusee;

class CheckAdress extends Alerts
{
    protected ?string $title =null;

    protected ?string $type = 'checkAdress';
    public function __construct()
    {
        $this->title = __('pipelines/onou/alerts.adresse_domicile') . ' : ';
    }
    public function handle(array $demande, \Closure $next)
    {
        // compute the age
        $id_commune = $demande['commune_residence_id'];

        $sex = ($demande['civilite'] == 1) ? 'garcons' : 'filles';
        $refused = Onou_heb_commune_refusee::where($sex, true)
            ->remember(60 * 60 * 12)
            ->select('commune')
            ->get();
        // filter if $id_commune exists in $refused
        $exist = $refused->contains($id_commune);

        if ($exist) {
            $this->status = 'danger';
            $this->message = __('pipelines/onou/alerts.adresse_domicile_not_conforme');
            $this->flush_alert();

        } else {
            $this->message = __('pipelines/onou/alerts.adresse_domicile_conforme');
            $this->flush_alert();
        }
        $next($demande);
    }
}
