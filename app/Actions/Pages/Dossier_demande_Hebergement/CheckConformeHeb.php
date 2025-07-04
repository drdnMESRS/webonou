<?php

namespace App\Actions\Pages\Dossier_demande_Hebergement;

use App\Pipelines\Onou\CheckAdress;
use App\Pipelines\Onou\CheckAge;
use Illuminate\Pipeline\Pipeline;

class CheckConformeHeb
{
    public function __construct(
        public ?array $demande
    ) {}

    /*
     * This action will handel the demande to the check all the validations creteria via Pipline
     */
    public function handle()
    {

        return app(Pipeline::class)
            ->send($this->demande)
            ->through([
                CheckAge::class,
                CheckAdress::class,
            ])
            ->thenReturn(
                'done'
            );

    }
}
