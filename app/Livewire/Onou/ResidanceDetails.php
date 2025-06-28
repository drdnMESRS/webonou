<?php

namespace App\Livewire\Onou;

use App\Actions\Pages\Residances\FindResidenceById;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ResidanceDetails extends Component
{
    /**
     * The residence ID.
     *
     * @var int
     */
    #[locked]
    public $residenceId;

    #[locked]
    public $showResidenceDetails = false;

    #[locked]
    public $loading = false;

    #[locked]
    public ?array $residence;

    #[On('residence-show')]
    public function UpdateshowResidenceDetails($id)
    {
        $this->dispatch('loader-show');
        $this->residence = (new FindResidenceById)->handle($id)->toArray();
        $this->showResidenceDetails = true;
    }

    public function render()
    {
        return view('livewire.onou.residance-details');
    }
}
