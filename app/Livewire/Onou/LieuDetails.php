<?php

namespace App\Livewire\Onou;

use App\Actions\Pages\GestionLieu\FindLieuById;
use App\Actions\Pages\Residances\FindResidenceById;
use App\Livewire\Onou\Forms\Lieu\PavilionForm;
use App\Models\Onou\Onou_cm_lieu;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class LieuDetails extends Component
{
    /*
     * The lieu ID.
     */
    #[Locked]
    public $lieuId;

    #[Locked]
    public $showLieuDetails = false;

    #[Locked]
    public ?array $lieu = null;

    #[Locked]
    public $loading = false;

    #[On('lieu-show')]
    public function UpdateshowLieuDetails($id)
    {
        $this->dispatch('loader-show');
        $this->lieuId = $id;
        // Find the lieu by ID and convert it to an array
        $this->lieu = (new FindLieuById)->handle($id)->toArray();
        // $this->residence = (new FindResidenceById)->handle($id)->toArray();
        $this->showLieuDetails = true;
        $this->dispatch('lieu-edit-data', $this->lieu); // Livewire: send ID to PavilionForm
    }

    public function deleteLieu()
    {
        $lieu = Onou_cm_lieu::withCount(['children', 'affectation'])->findOrFail($this->lieuId);

        if (($lieu->children_count !== null && $lieu->children_count > 0) ||
         ($lieu->Affectation_count !== null && $lieu->Affectation_count > 0)) {
            session()->flash('error', 'Impossible de supprimer ce lieu car il a des sous Lieux ou des affectations.');

        } else {
            try {
                $lieu->delete();
                session()->flash('success', 'Lieu est supprimé avec succès.');
            } catch (\Exception $e) {
                session()->flash('error', 'Impossible de supprimer ce lieu car il a des sous Lieux ou des affectations.');
                // session()->flash('error', $e->getMessage());
            }
        }
        $this->redirectRoute('onouLieu.show', navigate: true);

    }

    public function render()
    {
        return view('livewire.onou.lieu-details');
    }
}
