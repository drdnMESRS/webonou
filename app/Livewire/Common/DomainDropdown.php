<?php

namespace App\Livewire\Common;

use App\Livewire\Tables\OnouCmDemandeTable;
use App\Models\Lmd\Domain_lmd;
use App\Models\Lmd\Filiere_lmd;
use Livewire\Component;

class DomainDropdown extends Component
{
    public array $domains = [];

    public int $selectedDomain;

    public int $SelectedFiliere;

    public array $filieres = [];

    public function mount(array $pavillon = [], array $chambre = [])
    {
        $this->domains = cache()->remember(
            'domaine_'.auth()->id(),
            60 * 60 * 24,
            function () {
                return Domain_lmd::pluck('ll_domaine', 'id')
                    ->toArray();
            });
    }

    public function updatedSelectedDomain($value)
    {
        $this->loadFiliere($value);
    }

    public function updatedSelectedFiliere($value)
    {
        $this->dispatch('FilierefieldUpdateChanged', $value);
    }

    private function loadFiliere($DomainId)
    {
        $this->filieres = cache()->remember(
            'domain_filiere_'.$DomainId,
            60 * 60 * 24,
            function () use ($DomainId) {
                return Filiere_lmd::where('domainelmd', $DomainId)
                    ->pluck('ll_filiere', 'id')
                    ->prepend(__('livewire/tables/onou_cm_demande_table.select_filiere'), 0)
                    ->toArray();
            }
        );

    }

    public function filiereFilterChanged()
    {
        $this->dispatch('setFilter',
            filterKey: 'filiere',
            value: $this->SelectedFiliere
        )->to(OnouCmDemandeTable::class);
    }

    public function render()
    {
        return view('livewire.common.domain-dropdown');
    }
}
