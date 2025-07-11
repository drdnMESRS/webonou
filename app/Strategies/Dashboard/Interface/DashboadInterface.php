<?php

namespace App\Strategies\Dashboard\Interface;

interface DashboadInterface
{
    public function displayDashboard($stathb);

    public function getstat(): array;
}
