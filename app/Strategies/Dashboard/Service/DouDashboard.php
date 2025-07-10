<?php

namespace App\Strategies\Dashboard\Service;

use App\Actions\Sessions\RoleManagement;
use App\Models\Onou\Onou_cm_demande;
use App\Models\Onou\vm_heb_processing_by_dou;
use App\Models\Scopes\Dou\DouScope;
use App\Strategies\Dashboard\Interface\DashboadInterface;

use function PHPUnit\Framework\isEmpty;

class DouDashboard implements DashboadInterface
{

    public function displayDashboard($stathb)
    {

        return view(
            'dashboard',
            [
                'stathb' => $stathb,
            ]
        );
    }
    public function getstat(): array
    {


        return vm_heb_processing_by_dou::query()->first()->toArray();
    }


    }

