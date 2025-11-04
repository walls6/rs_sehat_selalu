<div wire:poll.5s class="h-screen w-screen overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 flex flex-col">
    <!-- Premium Compact Header -->
    <div class="flex-shrink-0 bg-gradient-to-r from-blue-900/90 via-indigo-900/90 to-purple-900/90 backdrop-blur-xl border-b border-white/10 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 lg:px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center shadow-2xl transform rotate-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white animate-pulse"></div>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-black text-white tracking-tight drop-shadow-lg">RS Sehat Selalu</h1>
                        <p class="text-blue-200 text-xs font-medium">Sistem Antrian Digital Premium</p>
                    </div>
                </div>
                <div class="text-right bg-white/10 backdrop-blur-lg rounded-xl px-4 py-2 border border-white/20">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-green-300 text-[10px] font-semibold uppercase">Live</span>
                    </div>
                    <div class="text-xl font-bold text-white" id="current-time">{{ now()->format('H:i:s') }}</div>
                    <div class="text-xs text-blue-200" id="current-date">{{ now()->format('d M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content - Scrollable Area -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden">
        <div class="max-w-7xl mx-auto px-4 lg:px-6 py-4">
            <!-- Flash Messages (Toast Style) -->
            @if(session('success'))
                <div class="fixed top-20 right-4 z-50 bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-xl shadow-2xl border-l-4 border-green-700 animate-fade-in max-w-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-bold text-sm">{{ session('success') }}</p>
                            <p class="text-green-100 text-xs mt-1">Simpan nomor antrian Anda</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="fixed top-20 right-4 z-50 bg-gradient-to-r from-red-500 to-rose-600 text-white px-6 py-4 rounded-xl shadow-2xl border-l-4 border-red-700 animate-fade-in max-w-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-semibold text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- My Antrian Card - Premium -->
            @if($myAntrian)
                <div class="mb-4 animate-fade-in">
                    <div class="bg-gradient-to-br from-emerald-500 via-green-600 to-teal-600 rounded-2xl shadow-2xl p-6 text-white border-4 border-emerald-400/50 relative overflow-hidden">
                        <div class="absolute inset-0 bg-white opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 30px 30px;"></div>
                        <div class="relative z-10">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-3 backdrop-blur-sm">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-bold mb-2">Nomor Antrian Anda</h2>
                                <div class="inline-block bg-white/20 text-white px-4 py-1 rounded-full text-xs font-bold mb-4">
                                    {{ $myAntrian->loket->nama_loket ?? 'Loket' }}
                                </div>
                                <div class="bg-white/20 rounded-xl p-6 backdrop-blur-md border-2 border-white/30">
                                    <div class="text-7xl lg:text-8xl font-black mb-3 tracking-wider drop-shadow-2xl" style="text-shadow: 0 0 30px rgba(255,255,255,0.5);">
                                        {{ $myAntrian->loket->code ?? '' }}{{ $myAntrian->nomor_antrian }}
                                    </div>
                                    <div class="flex items-center justify-center gap-3 text-xs">
                                        <div class="flex items-center gap-1.5 bg-white/20 px-3 py-1 rounded-full">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-semibold">Diambil: {{ $myAntrian->created_at->format('H:i:s') }}</span>
                                        </div>
                                        <div class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full font-bold">
                                            {{ ucfirst($myAntrian->status) }}
                                        </div>
                                    </div>
                                    <!-- Tombol Cetak -->
                                    <div class="mt-4 flex justify-center gap-3">
                                        <a
                                            href="{{ route('antrian.print', $myAntrian->id) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-2 bg-white text-emerald-600 font-bold py-3 px-6 rounded-xl shadow-lg hover:bg-emerald-50 transition-all transform hover:scale-105"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                            Cetak Tiket Antrian
                                        </a>
                                        <button
                                            onclick="printAntrian('{{ $myAntrian->loket->code ?? '' }}{{ $myAntrian->nomor_antrian }}', '{{ $myAntrian->loket->nama_loket ?? 'Loket' }}', '{{ $myAntrian->created_at->format('d/m/Y H:i:s') }}', '{{ ucfirst($myAntrian->status) }}')"
                                            class="inline-flex items-center gap-2 bg-emerald-500 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:bg-emerald-600 transition-all transform hover:scale-105"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                            Cetak Cepat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Current Called Section - Compact Premium (No Duplicates) -->
            @if(!empty($currentCalled) && count($currentCalled) > 0)
                <div class="mb-4 animate-fade-in">
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600/80 via-indigo-600/80 to-purple-600/80 backdrop-blur-sm p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-white/20 rounded-xl p-3 backdrop-blur-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold">Sedang Dipanggil</h2>
                                        <p class="text-blue-200 text-xs">Perhatikan nomor antrian</p>
                                    </div>
                                </div>
                                <div class="bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full animate-pulse flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-white rounded-full animate-ping"></span>
                                    <span>LIVE</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 bg-gradient-to-br from-gray-50/50 to-blue-50/50 backdrop-blur-sm">
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($currentCalled as $called)
                                    @php
                                        // Handle both array and object formats
                                        if (is_array($called)) {
                                            $loket = $called['loket'] ?? [];
                                            $loketName = $loket['nama_loket'] ?? 'Loket';
                                            $loketCode = $loket['code'] ?? '';
                                            $nomorAntrian = $called['nomor_antrian'] ?? '';
                                            $waktuPanggil = isset($called['waktu_panggil']) && $called['waktu_panggil'] 
                                                ? \Carbon\Carbon::parse($called['waktu_panggil'])->format('H:i:s') 
                                                : '-';
                                            $calledId = $called['id'] ?? uniqid();
                                            $loketId = $called['loket_id'] ?? null;
                                        } else {
                                            $loket = $called->loket ?? null;
                                            $loketName = $loket->nama_loket ?? 'Loket';
                                            $loketCode = $loket->code ?? '';
                                            $nomorAntrian = $called->nomor_antrian ?? '';
                                            $waktuPanggil = $called->waktu_panggil ? $called->waktu_panggil->format('H:i:s') : '-';
                                            $calledId = $called->id ?? uniqid();
                                            $loketId = $called->loket_id ?? null;
                                        }
                                    @endphp
                                    <div wire:key="called-{{ $calledId }}" class="bg-gradient-to-br from-white to-blue-50 rounded-xl p-4 border-2 border-blue-400 shadow-lg transform hover:scale-105 transition-all duration-300">
                                        <div class="text-center">
                                            <div class="mb-2">
                                                <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-3 py-1 rounded-lg text-xs font-bold">
                                                    {{ $loketName }}
                                                </span>
                                            </div>
                                            <div class="text-4xl lg:text-5xl font-black text-blue-600 mb-2 tracking-wider" style="font-family: 'Courier New', monospace;">
                                                {{ $loketCode }}{{ $nomorAntrian }}
                                            </div>
                                            <div class="flex items-center justify-center gap-1 text-[10px] text-gray-600 bg-gray-100 px-2 py-1 rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $waktuPanggil }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lokets Selection - Premium Grid -->
            <div class="mb-4">
                <div class="text-center mb-4">
                    <h2 class="text-2xl lg:text-3xl font-bold text-white mb-2 drop-shadow-lg">Pilih Layanan</h2>
                    <div class="w-20 h-1 bg-gradient-to-r from-blue-400 to-indigo-400 mx-auto rounded-full"></div>
                    <p class="text-blue-200 text-sm mt-2">Silakan pilih loket layanan yang Anda butuhkan</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($lokets as $loket)
                        <div wire:key="loket-{{ $loket->id }}" class="group bg-white/10 backdrop-blur-lg rounded-2xl shadow-xl overflow-hidden border-2 border-white/20 hover:border-blue-400/50 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                            <!-- Premium Card Header -->
                            <div class="bg-gradient-to-br from-blue-600/90 via-indigo-600/90 to-purple-600/90 backdrop-blur-sm p-5 text-white relative overflow-hidden">
                                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 50% 50%, white 2px, transparent 0); background-size: 15px 15px;"></div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 shadow-lg">
                                            <span class="text-3xl font-black">{{ $loket->code ?? 'L' }}</span>
                                        </div>
                                        <button
                                            wire:click="takeAntrian({{ $loket->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="takeAntrian({{ $loket->id }})"
                                            class="bg-white text-blue-600 font-bold py-2.5 px-4 rounded-xl hover:bg-blue-50 transition-all transform hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 shadow-xl text-xs"
                                        >
                                            <span wire:loading.remove wire:target="takeAntrian({{ $loket->id }})" class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Ambil
                                            </span>
                                            <span wire:loading wire:target="takeAntrian({{ $loket->id }})" class="flex items-center gap-1.5">
                                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>
                                                ...
                                            </span>
                                        </button>
                                    </div>
                                    <h3 class="text-xl font-bold mb-2">{{ $loket->nama_loket }}</h3>
                                    @if($loket->deskripsi)
                                        <p class="text-blue-100 text-xs leading-relaxed">{{ $loket->deskripsi }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Premium Card Footer -->
                            <div class="p-4 bg-gradient-to-br from-gray-50/50 to-white/50 backdrop-blur-sm">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="bg-blue-100 rounded-lg p-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-600 font-medium">Menunggu</div>
                                            <div class="text-2xl font-black text-blue-600">{{ $waitingCounts[$loket->id] ?? 0 }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[10px] text-gray-600 font-medium">Estimasi</div>
                                        <div class="text-sm font-bold text-gray-700">{{ ceil(($waitingCounts[$loket->id] ?? 0) * 5) }} mnt</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Compact Footer -->
            <div class="text-center py-3 mb-4">
                <button
                    wire:click="loadCurrentCalled"
                    wire:loading.attr="disabled"
                    wire:target="loadCurrentCalled"
                    class="inline-flex items-center gap-2 bg-blue-600/80 hover:bg-blue-700/80 backdrop-blur-sm text-white font-semibold py-2 px-5 rounded-xl shadow-lg transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed border border-white/20"
                >
                    <span wire:loading.remove wire:target="loadCurrentCalled" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Data
                    </span>
                    <span wire:loading wire:target="loadCurrentCalled" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Memperbarui...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
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

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.4);
    }
</style>
@endpush

@push('scripts')
<script>
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const dateString = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        
        const timeEl = document.getElementById('current-time');
        const dateEl = document.getElementById('current-date');
        if (timeEl) timeEl.textContent = timeString;
        if (dateEl) dateEl.textContent = dateString;
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Fungsi Cetak Nomor Antrian
    function printAntrian(nomorAntrian, namaLoket, waktuAmbil, status) {
        // Buat window baru untuk print
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        
        // Isi konten untuk print
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak Nomor Antrian - ${nomorAntrian}</title>
                <style>
                    @page {
                        size: A5 landscape;
                        margin: 20mm;
                    }
                    * {
                        margin: 0;
                        padding: 0;
                        box-sizing: border-box;
                    }
                    body {
                        font-family: 'Arial', sans-serif;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        padding: 20px;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                    }
                    .print-container {
                        background: white;
                        border-radius: 20px;
                        padding: 40px;
                        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                        text-align: center;
                        max-width: 600px;
                        width: 100%;
                    }
                    .header {
                        margin-bottom: 30px;
                    }
                    .header h1 {
                        color: #1e40af;
                        font-size: 28px;
                        margin-bottom: 10px;
                    }
                    .header p {
                        color: #64748b;
                        font-size: 14px;
                    }
                    .nomor-antrian {
                        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                        color: white;
                        padding: 40px;
                        border-radius: 15px;
                        margin: 30px 0;
                        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
                    }
                    .nomor-antrian .label {
                        font-size: 16px;
                        opacity: 0.9;
                        margin-bottom: 15px;
                    }
                    .nomor-antrian .nomor {
                        font-size: 72px;
                        font-weight: 900;
                        letter-spacing: 8px;
                        font-family: 'Courier New', monospace;
                    }
                    .info-section {
                        margin-top: 30px;
                        padding-top: 20px;
                        border-top: 2px dashed #e5e7eb;
                    }
                    .info-item {
                        display: flex;
                        justify-content: space-between;
                        padding: 10px 0;
                        font-size: 14px;
                    }
                    .info-item .label {
                        color: #64748b;
                        font-weight: 600;
                    }
                    .info-item .value {
                        color: #1e293b;
                        font-weight: 700;
                    }
                    .status-badge {
                        display: inline-block;
                        background: #fbbf24;
                        color: #92400e;
                        padding: 8px 20px;
                        border-radius: 20px;
                        font-weight: 700;
                        font-size: 14px;
                        margin-top: 15px;
                    }
                    .footer {
                        margin-top: 30px;
                        padding-top: 20px;
                        border-top: 2px dashed #e5e7eb;
                        font-size: 12px;
                        color: #94a3b8;
                    }
                    @media print {
                        body {
                            background: white;
                            padding: 0;
                        }
                        .print-container {
                            box-shadow: none;
                            padding: 30px;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="print-container">
                    <div class="header">
                        <h1>🏥 RS Sehat Selalu</h1>
                        <p>Sistem Antrian Digital</p>
                    </div>
                    <div class="nomor-antrian">
                        <div class="label">Nomor Antrian Anda</div>
                        <div class="nomor">${nomorAntrian}</div>
                    </div>
                    <div class="info-section">
                        <div class="info-item">
                            <span class="label">Layanan:</span>
                            <span class="value">${namaLoket}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Waktu:</span>
                            <span class="value">${waktuAmbil}</span>
                        </div>
                    </div>
                    <div class="status-badge">Status: ${status}</div>
                    <div class="footer">
                        <p>Harap simpan nomor antrian ini dan tunggu sampai dipanggil</p>
                        <p style="margin-top: 10px;">Terima kasih atas kunjungan Anda</p>
                    </div>
                </div>
            </body>
            </html>
        `);
        
        printWindow.document.close();
        
        // Tunggu konten dimuat, lalu print
        printWindow.onload = function() {
            setTimeout(function() {
                printWindow.print();
                // Tutup window setelah print (opsional)
                // printWindow.close();
            }, 250);
        };
    }
</script>
@endpush
