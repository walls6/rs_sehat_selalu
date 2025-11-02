<x-layouts.app>
<div wire:poll.6s="loadCalled" class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-4" >
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-8 pt-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Display Antrian</h1>
            <p class="text-gray-600">RS Sehat Selalu</p>
            <div class="flex items-center justify-center gap-2 mt-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-600">Live Update</span>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded max-w-2xl mx-auto" wire:ignore>
                <p class="font-bold">{{ session('success') }}</p>
                @if(session('nomor_antrian_baru'))
                    <p class="mt-2 text-lg">
                        Nomor Antrian Anda: <span class="font-black text-2xl">{{ session('nomor_antrian_baru') }}</span>
                    </p>
                @endif
            </div>
        @endif

        @if(session('nomor_antrian_baru'))
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-400 rounded-xl p-8 mb-8 max-w-2xl mx-auto text-center" wire:ignore>
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-green-800 mb-4">Nomor Antrian Anda</h2>
                <div class="text-7xl font-black text-green-700 mb-4">
                    {{ session('nomor_antrian_baru') }}
                </div>
                @if(session('loket_nama_baru'))
                    <p class="text-lg text-green-700 mb-2">{{ session('loket_nama_baru') }}</p>
                @endif
                <p class="text-sm text-green-600">Status: <span class="font-bold">Menunggu</span></p>
                <p class="text-xs text-green-500 mt-4">Silakan perhatikan layar ini untuk panggilan antrian Anda</p>
            </div>
        @endif

        @if(count($called) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($called as $antrian)
                    <div class="bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl p-8 shadow-2xl text-white transform transition-all hover:scale-105 animate-pulse">
                        <div class="text-center">
                            <div class="text-lg font-semibold mb-3 opacity-90 uppercase tracking-wide">
                                {{ $antrian->loket->nama_loket ?? 'Loket' }}
                            </div>
                            <div class="text-7xl font-black mb-4 drop-shadow-2xl">
                                {{ $antrian->loket->code ?? '' }}{{ $antrian->nomor_antrian }}
                            </div>
                            <div class="text-sm opacity-90 mt-4">
                                @if($antrian->waktu_panggil)
                                    <div class="flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Dipanggil: {{ $antrian->waktu_panggil->format('H:i:s') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl p-16 text-center shadow-lg">
                <div class="text-6xl mb-4">📋</div>
                <div class="text-2xl font-bold text-gray-400 mb-2">Tidak ada antrian yang sedang dipanggil</div>
                <div class="text-gray-300">Menunggu antrian dipanggil...</div>
            </div>
        @endif

        <!-- Info footer -->
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>Silakan perhatikan layar untuk nomor antrian Anda</p>
        </div>
    </div>
</div>
</x-layouts.app>


