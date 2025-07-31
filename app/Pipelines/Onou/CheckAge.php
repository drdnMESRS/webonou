<?php

namespace App\Pipelines\Onou;

use App\CommonClasses\Onou\Alerts;
use Carbon\Carbon;

class CheckAge extends Alerts
{
    protected ?string $title =null;

    protected ?string $type = 'checkAge';
    public function __construct()
    {
        $this->title = __('pipelines/onou/alerts.confirmite_age') . ' : ';
    }
    public function handle(array $demande, \Closure $next)
    {
        // compute the age
        $birthday = Carbon::parse($demande['date_naissance']);
        $age = $birthday->diffInYears(Carbon::now());
        if ($age > 28) {
            $this->status = 'danger';
            $this->message = __('pipelines/onou/alerts.age_not_conforme').' '.$birthday->diffForHumans(Carbon::now(), Carbon::DIFF_ABSOLUTE);

        } else {
            $this->message = __('pipelines/onou/alerts.age_conforme').' '.$birthday->diffForHumans(Carbon::now(), Carbon::DIFF_ABSOLUTE);

        }

        $existing = session('checks', []);
        $existing[$this->type] = [
            'status' => $this->status,
            'message' => $this->message,
            'title' => $this->title,
        ];
        session(['checks' => $existing]);
        $this->flush_alert();
        $next($demande);
    }
}
