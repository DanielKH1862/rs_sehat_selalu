<?php

namespace App\Livewire;

use App\Models\Antrian;
use Livewire\Component;

class DisplayAntrian extends Component
{
    public $antrianDipanggil = [];

    public function mount()
    {
        $this->loadAntrians();
    }

    public function loadAntrians()
    {
        $this->antrianDipanggil = Antrian::with('loket')
            ->where('status', 'dipanggil')
            ->orderBy('waktu_panggil', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.display-antrian');
    }
}
