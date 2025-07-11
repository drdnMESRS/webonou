<?php

namespace App\Strategies\Dashboard\Strategies;

use App\Models\Onou\vm_heb_processing_by_dou;
use App\Strategies\Dashboard\Interface\DashboadInterface;

class DouDashboard implements DashboadInterface
{
    public function displayDashboard($stathb)
    {
        return view('dashboard', ['stathb' => $stathb]);
    }

    public function getstat(): array
    {
        return (vm_heb_processing_by_dou::query()->first())
                ? vm_heb_processing_by_dou::query()->first()->toArray()
                : ['total' => 0, 'accepted' => 0, 'rejected' => 0, 'pending' => 0];
    }
}
