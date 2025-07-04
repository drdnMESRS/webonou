<?php

namespace App\Pipelines\Onou;

use App\CommonClasses\Onou\Alerts;
use Carbon\Carbon;

class CheckAge extends Alerts
{
    protected ?string $title = 'Confirmité d age : ';

    protected ?string $type = 'checkAge';

    public function handle(array $demande, \Closure $next)
    {
        // compute the age
        $birthday = Carbon::parse($demande['date_naissance']);
        $age = $birthday->diffInYears(Carbon::now());
        if ($age > 28) {
            $this->status = 'danger';
            $this->message = 'Age depassé '.$birthday->diffForHumans(Carbon::now(), Carbon::DIFF_ABSOLUTE);
            $this->flush_alert();

        } else {
            $this->message = 'Age conforme '.$birthday->diffForHumans(Carbon::now(), Carbon::DIFF_ABSOLUTE);
            $this->flush_alert();
        }
        $next($demande);
    }
}
