<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        @if(!$showSuccess)
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Ambil Nomor Antrian</h1>
                <p class="text-lg text-gray-600">Pilih loket yang Anda tuju</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($lokets as $loket)
                    <button 
                        wire:click="$set('selectedLoketId', {{ $loket->id }})"
                        class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-8 text-left border-2 {{ $selectedLoketId == $loket->id ? 'border-blue-500 ring-4 ring-blue-200' : 'border-transparent hover:border-blue-300' }}">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="ml-4 text-xl font-semibold text-gray-900">{{ $loket->nama_loket }}</h3>
                        </div>
                        @if($loket->deskripsi)
                            <p class="text-gray-600">{{ $loket->deskripsi }}</p>
                        @endif
                    </button>
                @endforeach
            </div>

            @error('selectedLoketId')
                <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ $message }}
                </div>
            @enderror

            <div class="mt-8 text-center">
                <button 
                    wire:click="ambilAntrian"
                    class="inline-flex items-center px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ !$selectedLoketId ? 'disabled' : '' }}>
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ambil Nomor Antrian
                </button>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-2xl p-12 text-center">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Nomor Antrian Anda</h2>
                
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-8 mb-6">
                    <p class="text-6xl font-bold text-white mb-2">{{ $nomorAntrian }}</p>
                    <p class="text-xl text-blue-100">
                        {{ $lokets->find($selectedLoketId)->nama_loket }}
                    </p>
                </div>
                
                <p class="text-gray-600 mb-8">Silakan tunggu nomor Anda dipanggil</p>
                
                <button 
                    wire:click="resetForm"
                    class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </button>
            </div>
        @endif
    </div>
</div>
