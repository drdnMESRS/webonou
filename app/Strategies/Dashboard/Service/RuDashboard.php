<?php

namespace App\Strategies\Dashboard\Service;

use App\Strategies\Dashboard\Interface\DashboadInterface;
use Illuminate\Support\Carbon;

class RuDashboard implements DashboadInterface
{

    public function displayDashboard($stathb)
    {

        $stathb = [];
        return view(
            'Home.home.home',
            [
                'stathb' => $stathb,
            ]
        );
    }
       public function getstat(): array
    {


        return [];
    }
}
