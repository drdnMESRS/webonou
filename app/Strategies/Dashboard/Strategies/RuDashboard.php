<?php

namespace App\Strategies\Dashboard\Strategies;

use App\Strategies\Dashboard\Interface\DashboadInterface;

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
