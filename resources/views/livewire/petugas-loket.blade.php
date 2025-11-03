<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Dashboard Petugas Loket
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola antrian dan panggil pasien dengan mudah</p>
                </div>
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg px-4 py-2 text-white shadow-md">
                        <div class="text-xs opacity-90">Petugas</div>
                        <div class="font-semibold">{{ auth()->user()->name ?? 'Petugas' }}</div>
                    </div>
                    <button 
                        type="button" 
                        wire:click="loadLists" 
                        class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all transform hover:scale-105"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-all transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-lg mb-6 shadow-md animate-fade-in">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-lg mb-6 shadow-md animate-fade-in">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Menunggu</p>
                        <p class="text-3xl font-bold mt-2">{{ count($waiting) }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Sedang Dipanggil</p>
                        <p class="text-3xl font-bold mt-2">{{ $called ? 1 : 0 }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Loket</p>
                        <p class="text-3xl font-bold mt-2">{{ count($lokets) }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Antrian yang Dipanggil -->
            <div class="bg-white rounded-2xl shadow-xl border-2 border-blue-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            Sedang Dipanggil
                        </h2>
                        @if($called)
                            <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse flex items-center gap-1">
                                <span class="w-2 h-2 bg-white rounded-full"></span>
                                LIVE
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-6">
                    @if($called && $called->loket)
                        <div id="called-panel" wire:key="called-{{ $called->id }}" class="relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-8 border-2 border-blue-300 animate-fade-in">
                            <div class="text-center">
                                <!-- Small top-right Selesai button (compact) -->
                                <button
                                    wire:click="finish({{ $called->id }})"
                                    onclick="if(!confirm('Yakin akan menyelesaikan antrian ini?')){ event.stopImmediatePropagation(); event.preventDefault(); return false; }"
                                    wire:loading.attr="disabled"
                                    wire:target="finish({{ $called->id }})"
                                    title="Selesai"
                                    class="absolute top-4 right-4 bg-green-500 hover:bg-green-600 text-white rounded-full p-2 shadow-lg focus:outline-none focus:ring-2 focus:ring-green-300 z-20"
                                >
                                    <span wire:loading.remove wire:target="finish({{ $called->id }})">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </span>
                                    <span wire:loading wire:target="finish({{ $called->id }})">
                                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                    </span>
                                </button>
                                <div class="mb-4">
                                    <span class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm font-semibold">
                                        {{ $called->loket->nama_loket ?? 'Loket' }}
                                    </span>
                                </div>
                                <div class="text-7xl md:text-8xl font-black text-blue-600 mb-4 tracking-wider drop-shadow-lg">
                                    {{ $called->loket->code ?? '' }}{{ $called->nomor_antrian }}
                                </div>
                                <div class="flex items-center justify-center gap-2 text-gray-600 mb-6">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold">Dipanggil: {{ $called->waktu_panggil ? $called->waktu_panggil->format('H:i:s') : '-' }}</span>
                                </div>
                            </div>
                            <button
                                wire:click="finish({{ $called->id }})"
                                onclick="if(!confirm('Yakin akan menyelesaikan antrian ini?')){ event.stopImmediatePropagation(); event.preventDefault(); return false; }"
                                wire:loading.attr="disabled"
                                wire:target="finish({{ $called->id }})"
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-6 rounded-xl text-lg shadow-lg transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            >
                                <span wire:loading.remove wire:target="finish({{ $called->id }})" class="flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Tandai Selesai
                                </span>
                                <span wire:loading wire:target="finish({{ $called->id }})" class="flex items-center gap-2">
                                    <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-xl p-12 text-center border-2 border-dashed border-gray-300">
                            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <div class="text-gray-500 text-xl font-semibold mb-2">Tidak ada antrian yang sedang dipanggil</div>
                            <div class="text-gray-400">Panggil antrian dari daftar menunggu</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Daftar Antrian Menunggu -->
            <div class="bg-white rounded-2xl shadow-xl border-2 border-yellow-200 overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-white">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            Antrian Menunggu
                        </h2>
                        @if(count($waiting) > 0)
                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                {{ count($waiting) }} Antrian
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="p-6">
                    @if(count($waiting) > 0)
                        <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($waiting as $index => $antrian)
                                <div wire:key="waiting-{{ $antrian->id }}" class="bg-gradient-to-r from-white to-gray-50 rounded-xl p-5 shadow-md hover:shadow-xl transition-all border-2 border-yellow-200 hover:border-yellow-400 transform hover:scale-[1.02]">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-4 flex-1">
                                            <div class="bg-yellow-500 text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-md">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-3xl font-black text-gray-800 mb-1">
                                                    {{ $antrian->loket->code ?? '' }}{{ $antrian->nomor_antrian ?? '-' }}
                                                </div>
                                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <span>{{ $antrian->loket->nama_loket ?? 'Loket' }}</span>
                                                    <span class="mx-2">•</span>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>Didaftar: {{ $antrian->created_at ? $antrian->created_at->format('H:i:s') : '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <button
                                            wire:click="callNow({{ $antrian->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="callNow({{ $antrian->id }})"
                                            title="Panggil {{ $antrian->loket->code ?? '' }}{{ $antrian->nomor_antrian }}"
                                            class="flex-shrink-0 w-14 h-14 rounded-xl flex items-center justify-center shadow-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white transition-all transform hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <span wire:loading.remove wire:target="callNow({{ $antrian->id }})">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                            </span>
                                            <span wire:loading wire:target="callNow({{ $antrian->id }})">
                                                <svg class="animate-spin h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-xl p-12 text-center border-2 border-dashed border-gray-300">
                            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <div class="text-gray-500 text-xl font-semibold mb-2">Tidak ada antrian menunggu</div>
                            <div class="text-gray-400">Semua antrian telah dipanggil</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
            animation: fade-in 0.3s ease-out;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</div>
