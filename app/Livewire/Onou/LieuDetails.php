<?php

namespace App\Livewire\Onou;

use App\Actions\Pages\GestionLieu\FindLieuById;
use App\Actions\Pages\Residances\FindResidenceById;
use Livewire\Attributes\On;
use App\Livewire\Onou\Forms\Lieu\PavilionForm;
use Livewire\Component;

class LieuDetails extends Component
{

   /*
    * The lieu ID.
    */
    public $lieuId;

    public $showLieuDetails = false;

    public ?array $lieu = null;

    public $loading = false;

    #[On('lieu-show')]
    public function UpdateshowLieuDetails($id)
    {
        $this->dispatch('loader-show');
        $this->lieuId = $id;
        // Find the lieu by ID and convert it to an array
        $this->lieu = (new FindLieuById)->handle($id)->toArray();
        //$this->residence = (new FindResidenceById)->handle($id)->toArray();
        $this->showLieuDetails = true;
         $this->dispatch('lieu-edit-data', $this->lieu); // Livewire: send ID to PavilionForm
    }

    public function render()
    {
        return view('livewire.onou.lieu-details');
    }
}
