<?php

namespace App\CommonClasses\Onou;

class Alerts
{
    protected ?string $status = 'success';

    protected ?string $title = ' ';

    protected ?string $message = '';

    protected ?string $type = '';

    public function flush_alert()
    {
        session()->flash('_alert.'.$this->type.'.status', $this->status);
        session()->flash('_alert.'.$this->type.'.title', $this->title);
        session()->flash('_alert.'.$this->type.'.message', $this->message);

    }
}
