<?php

namespace App\Livewire;

use App\Models\Loket;
use App\Models\Antrian;
use Livewire\Component;

class AmbilAntrian extends Component
{
    public $lokets;
    public $selectedLoketId = null;
    public $nomorAntrian = null;
    public $showSuccess = false;

    public function mount()
    {
        $this->lokets = Loket::all();
    }

    public function ambilAntrian()
    {
        $this->validate([
            'selectedLoketId' => 'required|exists:lokets,id',
        ], [
            'selectedLoketId.required' => 'Silakan pilih loket terlebih dahulu',
        ]);

        // Generate nomor antrian
        $nomorAntrian = Antrian::generateNomorAntrian($this->selectedLoketId);

        // Create antrian
        $antrian = Antrian::create([
            'loket_id' => $this->selectedLoketId,
            'nomor_antrian' => $nomorAntrian,
            'status' => 'menunggu',
        ]);

        $this->nomorAntrian = $antrian->nomor_antrian;
        $this->showSuccess = true;

        // Reset selection after 5 seconds
        $this->dispatch('antrian-diambil');
    }

    public function resetForm()
    {
        $this->selectedLoketId = null;
        $this->nomorAntrian = null;
        $this->showSuccess = false;
    }

    public function render()
    {
        return view('livewire.ambil-antrian');
    }
}
