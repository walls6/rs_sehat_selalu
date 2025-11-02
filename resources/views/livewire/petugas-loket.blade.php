<x-layouts.app>
<div wire:poll.6s="loadLists" class="container mx-auto p-6 max-w-7xl" >
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Petugas Loket</h1>
            <p class="text-gray-600 mt-1">Kelola antrian dan panggil pasien</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-sm text-gray-600">
                <span class="font-semibold">{{ auth()->user()->name ?? 'Petugas' }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded text-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4">
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4">
            <p class="font-bold">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Menampilkan antrian untuk semua loket</h2>
            <p class="text-sm text-gray-500">Anda dapat mengelola antrian dari halaman ini tanpa memilih loket.</p>
        </div>
    </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Antrian yang Dipanggil -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-blue-800 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        Sedang Dipanggil
                    </h2>
                    @if($called)
                        <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">LIVE</span>
                    @endif
                </div>
                @if($called && $called->loket)
                    <div class="bg-white rounded-xl p-8 shadow-xl border-2 border-blue-400">
                        <div class="text-center mb-6">
                            <div class="text-6xl font-black text-blue-600 mb-4 tracking-wider">
                                {{ $called->loket->code ?? '' }}{{ $called->nomor_antrian }}
                            </div>
                            <div class="text-lg font-semibold text-gray-700 mb-2">
                                Loket: {{ $called->loket->nama_loket ?? '-' }}
                            </div>
                            <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Dipanggil: {{ $called->waktu_panggil ? $called->waktu_panggil->format('H:i:s') : '-' }}
                            </div>
                        </div>
                        <button 
                            wire:click="finish({{ $called->id }})"
                            onclick="apiFinish(event, {{ $called->id }})"
                            wire:loading.attr="disabled"
                            wire:target="finish({{ $called->id }})"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-6 rounded-lg text-lg shadow-lg transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span class="flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="finish({{ $called->id }})">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Tandai Selesai
                                </span>
                                <span wire:loading wire:target="finish({{ $called->id }})" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </span>
                        </button>
                    </div>
                @else
                    <div class="bg-white rounded-xl p-12 text-center border-2 border-dashed border-blue-300">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <div class="text-gray-500 text-lg font-semibold">Tidak ada antrian yang sedang dipanggil</div>
                        <div class="text-gray-400 text-sm mt-2">Panggil antrian dari daftar menunggu</div>
                    </div>
                @endif
            </div>

            <!-- Daftar Antrian Menunggu -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border-2 border-yellow-300 rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-yellow-800 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01M7 8h.01"></path>
                        </svg>
                        Antrian Menunggu
                    </h2>
                    @if(count($waiting) > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ count($waiting) }} Antrian
                        </span>
                    @endif
                </div>
                @if(count($waiting) > 0)
                    <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                        @foreach($waiting as $index => $antrian)
                            <div class="bg-white rounded-lg p-4 shadow-md hover:shadow-xl transition-all border-2 border-yellow-200 hover:border-yellow-400 transform hover:scale-105">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">#{{ $index + 1 }}</span>
                                            <div>
                                                <div class="text-2xl font-bold text-gray-800">
                                                    {{ $antrian->loket->code ?? '' }}{{ $antrian->nomor_antrian ?? '-' }}
                                                </div>
                                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Didaftar: {{ $antrian->created_at ? $antrian->created_at->format('H:i:s') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button 
                                        wire:click="callNow({{ $antrian->id }})"
                                        onclick="apiCallNow(event, {{ $antrian->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="callNow({{ $antrian->id }})"
                                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg text-sm shadow-lg transition-all transform hover:scale-110 ml-4 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <span class="flex items-center gap-1">
                                            <span wire:loading.remove wire:target="callNow({{ $antrian->id }})">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                </svg>
                                                Panggil
                                            </span>
                                            <span wire:loading wire:target="callNow({{ $antrian->id }})" class="flex items-center gap-1">
                                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Memproses...
                                            </span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl p-12 text-center border-2 border-dashed border-yellow-300">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <div class="text-gray-500 text-lg font-semibold">Tidak ada antrian menunggu</div>
                        <div class="text-gray-400 text-sm mt-2">Semua antrian telah dipanggil</div>
                    </div>
                @endif
            </div>
        </div>
</div>
</x-layouts.app>


<div id="petugas-api-result" class="fixed top-6 left-6 z-50" aria-live="polite"></div>

@script
<script>
    function petugasCsrf() {
        const m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute('content') : '';
    }

    function petugasShow(msg, ok = true) {
        const container = document.getElementById('petugas-api-result');
        const el = document.createElement('div');
        el.className = (ok ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 'bg-red-100 border-l-4 border-red-500 text-red-700') + ' p-3 rounded shadow mb-2';
        el.innerText = msg;
        container.appendChild(el);
        setTimeout(() => el.remove(), 5000);
    }

    async function apiCallNow(e, id) {
        const btn = e && (e.currentTarget || e.target) ? (e.currentTarget || e.target) : null;
        if (btn) btn.disabled = true;
        try {
            const token = petugasCsrf();
            const res = await fetch(`/api/antrians/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ status: 'dipanggil' })
            });
            const data = await res.json();
            if (res.ok) {
                petugasShow('Antrian dipanggil: ' + (data.data.nomor_antrian || ''), true);
                if (window.Livewire) {
                    Livewire.emit('refreshList');
                    Livewire.emit('refreshDisplay');
                }
            } else {
                petugasShow((data.message || 'Gagal memanggil antrian') + (data.error ? ': ' + data.error : ''), false);
            }
        } catch (e) {
            petugasShow('Error: ' + e.message, false);
        } finally {
            if (btn) btn.disabled = false;
        }
    }

    async function apiFinish(e, id) {
        const btn = e && (e.currentTarget || e.target) ? (e.currentTarget || e.target) : null;
        if (btn) btn.disabled = true;
        try {
            const token = petugasCsrf();
            const res = await fetch(`/api/antrians/${id}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ status: 'selesai' })
            });
            const data = await res.json();
            if (res.ok) {
                petugasShow('Antrian selesai: ' + (data.data.nomor_antrian || ''), true);
                if (window.Livewire) {
                    Livewire.emit('refreshList');
                    Livewire.emit('refreshDisplay');
                }
            } else {
                petugasShow((data.message || 'Gagal menyelesaikan antrian') + (data.error ? ': ' + data.error : ''), false);
            }
        } catch (e) {
            petugasShow('Error: ' + e.message, false);
        } finally {
            if (btn) btn.disabled = false;
        }
    }
</script>
@endscript

