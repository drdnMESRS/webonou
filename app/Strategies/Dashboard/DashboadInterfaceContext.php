<?php

namespace App\Strategies\Dashboard;

use App\Actions\Sessions\RoleManagement;
use App\Strategies\Dashboard\Interface\DashboadInterface;
use App\Strategies\Dashboard\Strategies\Dashboard;
use App\Strategies\Dashboard\Strategies\DouDashboard;
use App\Strategies\Dashboard\Strategies\RuDashboard;

class DashboadInterfaceContext implements DashboadInterface
{
    private DashboadInterface $dashboard;

    public function __construct()
    {
        // TODO: Implement __construct() method. Create Object strategy based on auth user type.
        $type = app(RoleManagement::class)->get_active_type_etablissement();

        $this->dashboard = match ($type) {
            'DO' => new DouDashboard,
            'RU' => new RuDashboard,
            default => new Dashboard
        };
    }

    public function displayDashboard($stathb)
    {
        return $this->dashboard->displayDashboard($stathb);

    }

    public function getstat(): array
    {
        return $this->dashboard->getstat();
    }
}
