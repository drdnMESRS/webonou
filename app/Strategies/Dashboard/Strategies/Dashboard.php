<?php

namespace App\Strategies\Dashboard\Strategies;

use App\Models\Onou\vm_heb_processing_by_dou;
use App\Strategies\Dashboard\Interface\DashboadInterface;

class Dashboard implements DashboadInterface
{
    public function displayDashboard($stathb)
    {
        return view('dashboard', ['stathb' => $stathb]);
    }

    public function getstat(): array
    {

        return vm_heb_processing_by_dou::firstOrDefault();

    }
}
