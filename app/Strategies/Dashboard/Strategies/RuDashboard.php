<?php

namespace App\Strategies\Dashboard\Strategies;

use App\Models\Onou\vm_heb_processing_by_ru;
use App\Strategies\Dashboard\Interface\DashboadInterface;

class RuDashboard implements DashboadInterface
{
    public function displayDashboard($stathb)
    {

        return view('dashboard', ['stathb' => $stathb]);
    }

    public function getstat(): array
    {
        return vm_heb_processing_by_ru::firstOrDefault();
    }
}
