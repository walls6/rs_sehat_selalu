<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <!-- Premium Header with Hospital Logo Style -->
    <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 text-white shadow-2xl border-b-4 border-blue-700">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="bg-white rounded-full p-4 shadow-xl">
                        <svg class="w-12 h-12 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold mb-2 tracking-tight">RS Sehat Selalu</h1>
                        <p class="text-blue-200 text-lg font-medium">Sistem Antrian Digital Premium</p>
                        <p class="text-blue-300 text-sm mt-1">Terpercaya • Modern • Profesional</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-blue-200 mb-1">Waktu Saat Ini</div>
                    <div class="text-2xl font-bold" id="current-time">{{ now()->format('H:i:s') }}</div>
                    <div class="text-sm text-blue-200">{{ now()->format('d F Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 border-l-4 border-green-700 text-white px-6 py-4 rounded-lg mb-6 shadow-xl animate-fade-in">
                <div class="flex items-center gap-3">
                    <svg class="w-7 h-7 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-lg">{{ session('success') }}</p>
                        <p class="text-green-100 text-sm mt-1">Simpan nomor antrian Anda dengan baik</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-gradient-to-r from-red-500 to-rose-600 border-l-4 border-red-700 text-white px-6 py-4 rounded-lg mb-6 shadow-xl animate-fade-in">
                <div class="flex items-center gap-3">
                    <svg class="w-7 h-7 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-semibold text-lg">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Premium My Antrian Card -->
        @if($myAntrian)
            <div class="bg-gradient-to-br from-emerald-500 via-green-600 to-teal-600 rounded-3xl shadow-2xl p-10 mb-8 text-white border-4 border-emerald-400 animate-fade-in relative overflow-hidden">
                <div class="absolute inset-0 bg-white opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
                <div class="relative z-10">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-30 rounded-full mb-4 backdrop-blur-sm">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold mb-2">Nomor Antrian Anda</h2>
                        <p class="text-emerald-100 text-sm">Silakan simpan dan tunggu panggilan</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-3xl p-8 backdrop-blur-md border-2 border-white border-opacity-30">
                        <div class="text-center">
                            <div class="inline-block bg-white bg-opacity-30 text-white px-6 py-2 rounded-full text-sm font-bold mb-6">
                                {{ $myAntrian->loket->nama_loket ?? 'Loket' }}
                            </div>
                            <div class="text-9xl md:text-[12rem] font-black mb-6 tracking-wider drop-shadow-2xl text-white" style="text-shadow: 0 0 30px rgba(255,255,255,0.5);">
                                {{ $myAntrian->loket->code ?? '' }}{{ $myAntrian->nomor_antrian }}
                            </div>
                            <div class="flex items-center justify-center gap-4 text-sm">
                                <div class="flex items-center gap-2 bg-white bg-opacity-20 px-4 py-2 rounded-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold">Diambil: {{ $myAntrian->created_at->format('H:i:s') }}</span>
                                </div>
                                <div class="px-4 py-2 bg-yellow-400 text-yellow-900 rounded-full font-bold shadow-lg">
                                    {{ ucfirst($myAntrian->status) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Premium Current Called Display -->
        @if(count($currentCalled) > 0)
            <div class="bg-white rounded-3xl shadow-2xl border-4 border-blue-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-8 text-white relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(45deg, transparent 25%, rgba(255,255,255,.1) 25%, rgba(255,255,255,.1) 50%, transparent 50%, transparent 75%, rgba(255,255,255,.1) 75%, rgba(255,255,255,.1)); background-size: 30px 30px;"></div>
                    <div class="relative z-10 flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="bg-white bg-opacity-20 rounded-2xl p-4 backdrop-blur-sm">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold mb-1">Sedang Dipanggil</h2>
                                <p class="text-blue-200 text-sm">Perhatikan nomor antrian yang dipanggil</p>
                            </div>
                        </div>
                        <div class="bg-green-500 text-white text-sm font-bold px-5 py-3 rounded-full animate-pulse flex items-center gap-2 shadow-lg">
                            <span class="w-3 h-3 bg-white rounded-full animate-ping"></span>
                            <span>LIVE UPDATE</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-8 bg-gradient-to-br from-gray-50 to-blue-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($currentCalled as $called)
                            <div wire:key="called-{{ $called->id }}" class="bg-gradient-to-br from-white to-blue-50 rounded-2xl p-8 border-4 border-blue-400 shadow-xl transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                                <div class="text-center">
                                    <div class="mb-4">
                                        <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg">
                                            {{ $called->loket->nama_loket ?? 'Loket' }}
                                        </span>
                                    </div>
                                    <div class="text-6xl font-black text-blue-600 mb-4 tracking-wider drop-shadow-lg" style="font-family: 'Courier New', monospace;">
                                        {{ $called->loket->code ?? '' }}{{ $called->nomor_antrian }}
                                    </div>
                                    <div class="flex items-center justify-center gap-2 text-xs text-gray-600 bg-gray-100 px-3 py-2 rounded-full">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Dipanggil: {{ $called->waktu_panggil ? $called->waktu_panggil->format('H:i:s') : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Premium Lokets Selection -->
        <div class="mb-8">
            <div class="text-center mb-10">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Pilih Layanan</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-indigo-600 mx-auto rounded-full"></div>
                <p class="text-gray-600 text-lg mt-4">Silakan pilih loket layanan yang Anda butuhkan</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($lokets as $loket)
                    <div wire:key="loket-{{ $loket->id }}" class="group bg-white rounded-3xl shadow-xl overflow-hidden border-4 border-gray-200 hover:border-blue-500 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                        <!-- Premium Card Header -->
                        <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 p-8 text-white relative overflow-hidden">
                            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 50% 50%, white 2px, transparent 0); background-size: 20px 20px;"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-5 shadow-lg">
                                        <span class="text-4xl font-black">{{ $loket->code ?? 'L' }}</span>
                                    </div>
                                    <button
                                        wire:click="takeAntrian({{ $loket->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="takeAntrian({{ $loket->id }})"
                                        class="bg-white text-blue-600 font-bold py-4 px-6 rounded-2xl hover:bg-blue-50 transition-all transform hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 shadow-xl hover:shadow-2xl"
                                    >
                                        <span wire:loading.remove wire:target="takeAntrian({{ $loket->id }})" class="flex items-center gap-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Ambil Antrian
                                        </span>
                                        <span wire:loading wire:target="takeAntrian({{ $loket->id }})" class="flex items-center gap-2">
                                            <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            Memproses...
                                        </span>
                                    </button>
                                </div>
                                <h3 class="text-3xl font-bold mb-3">{{ $loket->nama_loket }}</h3>
                                @if($loket->deskripsi)
                                    <p class="text-blue-100 text-sm leading-relaxed">{{ $loket->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Premium Card Footer -->
                        <div class="p-8 bg-gradient-to-br from-gray-50 to-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-100 rounded-xl p-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 font-medium">Antrian Menunggu</div>
                                        <div class="text-3xl font-black text-blue-600">{{ $waitingCounts[$loket->id] ?? 0 }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-gray-500 font-medium">Estimasi Waktu</div>
                                    <div class="text-lg font-bold text-gray-700">{{ ceil(($waitingCounts[$loket->id] ?? 0) * 5) }} menit</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Premium Footer Info -->
        <div class="text-center py-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-200">
            <div class="flex items-center justify-center gap-4">
                <button
                    wire:click="loadCurrentCalled"
                    wire:loading.attr="disabled"
                    wire:target="loadCurrentCalled"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="loadCurrentCalled">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Data
                    </span>
                    <span wire:loading wire:target="loadCurrentCalled" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Memperbarui...
                    </span>
                </button>
                <div class="text-gray-600 text-sm">
                    <p class="font-medium">Tekan tombol Refresh untuk memperbarui data</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
            }
            50% {
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.8);
            }
        }

        .premium-card {
            animation: pulse-glow 3s ease-in-out infinite;
        }
    </style>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateString = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            const timeEl = document.getElementById('current-time');
            if (timeEl) {
                timeEl.textContent = timeString;
            }
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</div>
