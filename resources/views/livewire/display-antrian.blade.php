<div class="h-full w-full flex flex-col overflow-hidden">
    <div wire:poll.3s class="flex-1 flex flex-col p-4 lg:p-6 overflow-hidden">
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Compact Premium Header -->
            <div class="flex-shrink-0 mb-4 animate-fade-in">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="relative animate-float">
                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center shadow-2xl transform rotate-3">
                                <span class="text-white font-black text-xl">RS</span>
                            </div>
                            <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white animate-pulse"></div>
                        </div>
                        <div>
                            <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight drop-shadow-lg">
                                Display Antrian
                            </h1>
                            <p class="text-blue-200 text-sm font-medium">Rumah Sakit Sehat Selalu</p>
                        </div>
                    </div>
                    <div class="text-right bg-white/10 backdrop-blur-lg rounded-xl px-4 py-2 border border-white/20 shadow-xl">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="text-green-300 text-[10px] font-semibold uppercase tracking-wider">Live</span>
                        </div>
                        <div class="text-2xl lg:text-3xl font-bold text-white" id="clock-time">--:--:--</div>
                        <div class="text-xs text-blue-200 font-medium" id="clock-date">--/--/----</div>
                    </div>
                </div>
            </div>

            <!-- Main Display Grid - Flex Layout -->
            <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 gap-4 overflow-hidden min-h-0">
                <!-- Cards Loket - Premium Design -->
                <div class="lg:col-span-8 grid grid-cols-2 xl:grid-cols-3 gap-3 lg:gap-4 overflow-hidden">
                    @forelse($lokets as $loket)
                        @php
                            $currentAntrian = $called->where('loket_id', $loket->id)->first();
                        @endphp
                        <div class="relative group animate-slide-up flex flex-col min-h-0" wire:key="loket-{{ $loket->id }}" style="animation-delay: {{ $loop->index * 0.05 }}s">
                            <!-- Glow Effect -->
                            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-400 via-indigo-400 to-purple-400 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-1000"></div>
                            
                            <!-- Main Card -->
                            <div class="relative flex-1 bg-gradient-to-br from-white via-blue-50 to-indigo-50 rounded-2xl p-4 lg:p-5 shadow-2xl border-2 border-white/50 backdrop-blur-sm {{ $currentAntrian ? 'animate-pulse-glow' : '' }} flex flex-col min-h-0">
                                <!-- Decorative Elements -->
                                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-blue-200/20 to-indigo-200/20 rounded-full blur-2xl"></div>
                                <div class="absolute bottom-0 left-0 w-16 h-16 bg-gradient-to-tr from-purple-200/20 to-pink-200/20 rounded-full blur-xl"></div>
                                
                                <div class="relative z-10 flex flex-col flex-1 min-h-0">
                                    <!-- Loket Header -->
                                    <div class="text-center mb-3 flex-shrink-0">
                                        <div class="inline-flex items-center gap-1.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-3 py-1 rounded-full shadow-lg">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <span class="font-bold text-xs">{{ $loket->nama_loket }}</span>
                                        </div>
                                    </div>

                                    <div class="flex-1 flex items-center justify-center min-h-0">
                                        @if($currentAntrian)
                                            <!-- Nomor Antrian Aktif -->
                                            <div class="text-center w-full">
                                                <p class="text-gray-600 text-[10px] font-semibold uppercase tracking-wider mb-2">Saat Ini</p>
                                                <div class="relative inline-block">
                                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl blur-lg opacity-50"></div>
                                                    <div class="relative bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl px-4 py-3 shadow-2xl">
                                                        <p class="text-4xl lg:text-5xl xl:text-6xl font-black text-white tracking-wider drop-shadow-2xl" wire:key="antrian-{{ $currentAntrian->id }}">
                                                            {{ $loket->code }}{{ $currentAntrian->nomor_antrian }}
                                                        </p>
                                                    </div>
                                                </div>
                                                @if($currentAntrian->waktu_panggil)
                                                    <div class="inline-flex items-center gap-1.5 mt-2 bg-white/80 backdrop-blur-sm rounded-full px-2.5 py-1 shadow-lg border border-gray-200">
                                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span class="text-gray-700 font-semibold text-[10px]">{{ $currentAntrian->waktu_panggil->format('H:i:s') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <!-- Empty State -->
                                            <div class="text-center">
                                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-2">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-gray-500 font-medium text-xs">Belum ada antrian</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 xl:col-span-3 bg-white/10 backdrop-blur-lg rounded-2xl p-8 text-center shadow-xl border border-white/20 flex items-center justify-center">
                            <div>
                                <div class="text-4xl mb-2">📋</div>
                                <div class="text-xl font-bold text-white mb-1">Belum ada loket</div>
                                <div class="text-blue-200 text-sm">Hubungi administrator</div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Sidebar: Riwayat Panggilan -->
                <div class="lg:col-span-4 flex flex-col min-h-0">
                    <div class="flex-1 bg-white/10 backdrop-blur-lg rounded-2xl p-4 shadow-2xl border border-white/20 flex flex-col min-h-0">
                        <div class="flex items-center justify-between mb-3 flex-shrink-0">
                            <h3 class="text-lg font-black text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Riwayat
                            </h3>
                            <span class="text-[10px] text-blue-200 bg-blue-500/30 px-2 py-0.5 rounded-full">Max 10</span>
                        </div>
                        <div class="flex-1 space-y-2 overflow-y-auto pr-1 custom-scrollbar min-h-0">
                            @forelse($called->take(10) as $index => $item)
                                <div class="flex items-center justify-between rounded-xl bg-gradient-to-r from-blue-500/20 to-indigo-500/20 backdrop-blur-sm border border-white/20 px-3 py-2 hover:from-blue-500/30 hover:to-indigo-500/30 transition-all duration-300 animate-slide-up flex-shrink-0" style="animation-delay: {{ $index * 0.03 }}s">
                                    <div class="min-w-0 pr-2 flex-1">
                                        <div class="text-xs font-bold text-white truncate mb-0.5">{{ optional($item->loket)->nama_loket ?? '—' }}</div>
                                        <div class="text-[10px] text-blue-200 flex items-center gap-1">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ optional($item->waktu_panggil)->format('H:i:s') ?? '—' }}
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg px-3 py-1.5 shadow-lg">
                                            <div class="text-lg font-black text-white">{{ $item->nomor_antrian }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-blue-200 py-8 flex items-center justify-center h-full">
                                    <div>
                                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <p class="font-medium text-sm">Belum ada panggilan</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compact Footer -->
            <div class="flex-shrink-0 text-center mt-3 animate-fade-in">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-lg rounded-full px-4 py-1.5 border border-white/20 shadow-xl">
                    <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p class="text-white font-semibold text-xs">Perhatikan layar untuk nomor antrian Anda. Terima kasih.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }
    </style>
</div>
