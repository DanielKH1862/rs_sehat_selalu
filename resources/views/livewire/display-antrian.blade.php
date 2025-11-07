<div class="min-h-screen bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900 text-white" wire:poll.3s="loadAntrians">
    <div class="container mx-auto px-8 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-6xl font-bold mb-4 drop-shadow-lg">RS Sehat Selalu</h1>
            <p class="text-3xl text-blue-200">Sistem Pemanggilan Antrian</p>
            <div class="mt-4 text-2xl text-blue-300">
                {{ now()->format('d F Y - H:i:s') }}
            </div>
            <!-- Audio Enable Button -->
            <div class="mt-6">
                <button id="enableAudioBtn" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:scale-105">
                    🔊 Aktifkan Suara Pemanggilan
                </button>
                <button id="disableAudioBtn" class="hidden bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all duration-300 transform hover:scale-105">
                    🔇 Nonaktifkan Suara
                </button>
            </div>
        </div>

        <!-- Display Antrian yang Dipanggil -->
        @if(count($antrianDipanggil) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($antrianDipanggil as $antrian)
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border-2 border-white/20 shadow-2xl transform hover:scale-105 transition-all duration-300 animate-pulse" 
                         data-queue-id="{{ $antrian->id }}"
                         data-queue-number="{{ $antrian->nomor_antrian }}"
                         data-counter-name="{{ $antrian->loket->nama_loket }}">
                        <div class="text-center">
                            <p class="text-xl text-blue-200 mb-2">Nomor Antrian</p>
                            <p class="text-7xl font-bold mb-4 text-yellow-300 drop-shadow-lg">
                                {{ $antrian->nomor_antrian }}
                            </p>
                            <div class="h-1 w-24 bg-yellow-300 mx-auto mb-4 rounded-full"></div>
                            <p class="text-3xl font-semibold text-white mb-2">
                                {{ $antrian->loket->nama_loket }}
                            </p>
                            <p class="text-lg text-blue-200">
                                Dipanggil: {{ $antrian->waktu_panggil->format('H:i:s') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-16 inline-block border-2 border-white/20">
                    <svg class="w-32 h-32 mx-auto mb-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-4xl text-blue-200">Menunggu Pemanggilan...</p>
                </div>
            </div>
        @endif

        <!-- Footer Info -->
        <div class="mt-16 text-center">
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 inline-block border border-white/20">
                <p class="text-xl text-blue-200">Harap perhatikan nomor antrian Anda</p>
                <p class="text-lg text-blue-300 mt-2">Terima kasih atas kesabaran Anda</p>
            </div>
        </div>
    </div>

<script>
    // Text-to-Speech functionality with user activation
    let announcedQueues = new Set();
    let audioEnabled = false;
    
    function speakQueueNumber() {
        // Only speak if audio is enabled by user
        if (!audioEnabled) {
            return;
        }
        
        // Check if browser supports speech synthesis
        if ('speechSynthesis' in window) {
            // Get all queue numbers currently displayed
            const queueElements = document.querySelectorAll('[data-queue-number]');
            
            queueElements.forEach(element => {
                const queueNumber = element.getAttribute('data-queue-number');
                const counterName = element.getAttribute('data-counter-name');
                const queueId = element.getAttribute('data-queue-id');
                
                // Only announce if not already announced
                if (!announcedQueues.has(queueId)) {
                    announcedQueues.add(queueId);
                    
                    // Announce 3 times with 10 second delay
                    for (let i = 0; i < 3; i++) {
                        setTimeout(() => {
                            // Double check audio is still enabled before speaking
                            if (!audioEnabled) return;
                            
                            // Create speech synthesis utterance
                            const utterance = new SpeechSynthesisUtterance();
                            utterance.text = `Nomor antrian ${queueNumber}, silakan menuju ${counterName}`;
                            utterance.lang = 'id-ID'; // Indonesian language
                            utterance.rate = 0.9; // Slightly slower for clarity
                            utterance.pitch = 1;
                            utterance.volume = 1;
                            
                            // Speak the text (only works after user activation)
                            window.speechSynthesis.speak(utterance);
                        }, i * 10000); // 10 seconds delay (10000ms) between each announcement
                    }
                }
            });
        }
    }
    
    // Enable audio button handler
    document.addEventListener('DOMContentLoaded', function() {
        const enableBtn = document.getElementById('enableAudioBtn');
        const disableBtn = document.getElementById('disableAudioBtn');
        
        // Enable audio on button click (user activation)
        enableBtn.addEventListener('click', function() {
            audioEnabled = true;
            enableBtn.classList.add('hidden');
            disableBtn.classList.remove('hidden');
            
            // Test speech synthesis with user activation
            if ('speechSynthesis' in window) {
                const testUtterance = new SpeechSynthesisUtterance('Suara pemanggilan telah diaktifkan');
                testUtterance.lang = 'id-ID';
                window.speechSynthesis.speak(testUtterance);
            }
            
            // Check for any current queues to announce
            speakQueueNumber();
        });
        
        // Disable audio button handler
        disableBtn.addEventListener('click', function() {
            audioEnabled = false;
            disableBtn.classList.add('hidden');
            enableBtn.classList.remove('hidden');
            
            // Cancel any ongoing speech
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }
        });
    });
    
    // Run after Livewire updates (when polling updates the data)
    document.addEventListener('livewire:load', function() {
        Livewire.hook('message.processed', (message, component) => {
            speakQueueNumber();
        });
    });
    
    // Clean up old announced queues periodically (keep last 50)
    setInterval(() => {
        if (announcedQueues.size > 50) {
            const arr = Array.from(announcedQueues);
            announcedQueues = new Set(arr.slice(-50));
        }
    }, 60000); // Every minute
</script>

<style>
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
</div>