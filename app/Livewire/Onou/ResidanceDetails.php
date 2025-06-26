<?php

namespace App\Livewire\Onou;

use App\Actions\Pages\Residances\FindResidenceById;
use App\DTO\Onou\ResidanceDTO;
use App\Models\Onou\Onou_cm_etablissement;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use phpDocumentor\Reflection\Types\Collection;

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
    public ? array $residence ;

    #[On('residence-show')]
    public function UpdateshowResidenceDetails($id)
    {
        $this->dispatch('loader-show')->self();
        $this->residence = (new FindResidenceById)->handle($id)->toArray();
        $this->showResidenceDetails = true;
    }

    #[On('loader-show')]
    public function showloader()
    {
        $this->loading = true;
        $this->showResidenceDetails = true;
    }


    public function render()
    {
        return view('livewire.onou.residance-details');
    }
}
