<?php

namespace App\Livewire;

use App\Models\Loket;
use App\Models\Antrian;
use Livewire\Component;

class PetugasLoket extends Component
{
    public $lokets;
    public $selectedLoketId = null;
    public $antrianMenunggu = [];
    public $antrianDipanggil = null;

    public function mount()
    {
        $this->lokets = Loket::all();
    }

    public function selectLoket($loketId)
    {
        $this->selectedLoketId = $loketId;
        $this->loadAntrians();
    }

    public function loadAntrians()
    {
        if ($this->selectedLoketId) {
            $this->antrianMenunggu = Antrian::with('loket')
                ->where('loket_id', $this->selectedLoketId)
                ->where('status', 'menunggu')
                ->orderBy('created_at')
                ->get();

            $this->antrianDipanggil = Antrian::with('loket')
                ->where('loket_id', $this->selectedLoketId)
                ->where('status', 'dipanggil')
                ->first();
        }
    }

    public function panggilAntrian($antrianId)
    {
        $antrian = Antrian::find($antrianId);
        
        if ($antrian) {
            $antrian->status = 'dipanggil';
            $antrian->waktu_panggil = now();
            $antrian->save();

            $this->loadAntrians();
            $this->dispatch('antrian-dipanggil');
        }
    }

    public function selesaiAntrian($antrianId)
    {
        $antrian = Antrian::find($antrianId);
        
        if ($antrian) {
            $antrian->status = 'selesai';
            $antrian->save();

            $this->loadAntrians();
            $this->dispatch('antrian-selesai');
        }
    }

    public function render()
    {
        return view('livewire.petugas-loket');
    }
}
