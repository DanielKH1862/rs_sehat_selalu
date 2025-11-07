<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8" wire:poll.3s="loadAntrians">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Panel Petugas Loket</h1>
            <p class="text-gray-600">Kelola antrian pasien</p>
        </div>

        @if(!$selectedLoketId)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Pilih Loket Anda</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($lokets as $loket)
                        <button 
                            wire:click="selectLoket({{ $loket->id }})"
                            class="p-4 bg-blue-50 hover:bg-blue-100 border-2 border-blue-200 hover:border-blue-400 rounded-lg transition-all duration-200 text-left">
                            <h3 class="font-semibold text-gray-900">{{ $loket->nama_loket }}</h3>
                            @if($loket->deskripsi)
                                <p class="text-sm text-gray-600 mt-1">{{ $loket->deskripsi }}</p>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mb-6 bg-white rounded-lg shadow-md p-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ $lokets->find($selectedLoketId)->nama_loket }}
                    </h2>
                    <p class="text-sm text-gray-600">Loket aktif</p>
                </div>
                <button 
                    wire:click="$set('selectedLoketId', null)"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-all duration-200">
                    Ganti Loket
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Antrian Sedang Dipanggil -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        Sedang Dipanggil
                    </h3>
                    
                    @if($antrianDipanggil)
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg p-6 text-white mb-4">
                            <p class="text-4xl font-bold mb-2">{{ $antrianDipanggil->nomor_antrian }}</p>
                            <p class="text-sm text-green-100">
                                Dipanggil: {{ $antrianDipanggil->waktu_panggil->format('H:i:s') }}
                            </p>
                        </div>
                        
                        <button 
                            wire:click="selesaiAntrian({{ $antrianDipanggil->id }})"
                            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Selesai
                        </button>
                    @else
                        <div class="text-center py-12 text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p>Tidak ada antrian yang sedang dipanggil</p>
                        </div>
                    @endif
                </div>

                <!-- Daftar Antrian Menunggu -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Antrian Menunggu
                        <span class="ml-auto bg-yellow-100 text-yellow-800 text-sm font-semibold px-3 py-1 rounded-full">
                            {{ count($antrianMenunggu) }}
                        </span>
                    </h3>
                    
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($antrianMenunggu as $antrian)
                            <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200">
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ $antrian->nomor_antrian }}</p>
                                    <p class="text-xs text-gray-500">{{ $antrian->created_at->format('H:i:s') }}</p>
                                </div>
                                <button 
                                    wire:click="panggilAntrian({{ $antrian->id }})"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200"
                                    {{ $antrianDipanggil ? 'disabled' : '' }}>
                                    Panggil
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-12 text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p>Tidak ada antrian menunggu</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
