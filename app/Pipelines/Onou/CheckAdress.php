<?php

namespace App\Pipelines\Onou;

use App\CommonClasses\Onou\Alerts;
use App\Models\Onou\Onou_heb_commune_refusee;

class CheckAdress extends Alerts
{
    protected ?string $title = 'Adresse du domicile : ';

    protected ?string $type = 'checkAdress';

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
            $this->message = 'Adresse du domicile réfusée ';
            $this->flush_alert();

        } else {
            $this->message = 'Adresse du domicile conforme ';
            $this->flush_alert();
        }
        $next($demande);
    }
}
