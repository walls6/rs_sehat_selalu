<div>
    <div wire:poll.3s class="p-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex items-start justify-between mb-6 pt-6">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">RS</div>
                        <div>
                            <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Display Antrian</h1>
                            <p class="text-sm text-gray-600">RS Sehat Selalu</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            Live Update
                        </span>
                        <span class="hidden md:inline text-xs text-gray-500">Layar ini diperbarui otomatis</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-semibold text-gray-800">
                        <span id="clock-time">--:--:--</span>
                    </div>
                    <div class="text-xs text-gray-500">
                        <span id="clock-date">--/--/----</span>
                    </div>
                </div>
            </div>

            <!-- Notif sukses ambil nomor (opsional ditampilkan di layar ini) -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 p-4 mb-6 rounded-lg max-w-3xl">
                    <p class="font-semibold">{{ session('success') }}</p>
                    @if(session('nomor_antrian_baru'))
                        <p class="mt-1 text-base">Nomor Antrian Anda: <span class="font-black text-2xl">{{ session('nomor_antrian_baru') }}</span></p>
                    @endif
                </div>
            @endif

            <!-- Main content: grid loket + sidebar riwayat -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Cards loket -->
                <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($lokets as $loket)
                        @php
                            $currentAntrian = $called->where('loket_id', $loket->id)->first();
                        @endphp
                        <div class="relative overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-emerald-100 p-6" wire:key="loket-{{ $loket->id }}">
                            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                                <div class="absolute -right-16 -bottom-16 w-64 h-64 rounded-full bg-gradient-to-tr from-emerald-50 to-cyan-50"></div>
                                <div class="absolute -left-20 -top-20 w-56 h-56 rounded-full bg-gradient-to-tr from-cyan-50 to-emerald-50"></div>
                            </div>
                            <div class="relative">
                                <h2 class="text-lg font-semibold text-gray-800 text-center mb-3">{{ $loket->nama_loket }}</h2>
                                @if($currentAntrian)
                                    <div class="text-center">
                                        <p class="text-gray-500 text-xs mb-1">Nomor Antrian Saat Ini</p>
                                        <p class="text-6xl md:text-7xl font-black tracking-wider text-emerald-700 drop-shadow-sm" wire:key="antrian-{{ $currentAntrian->id }}">
                                            {{ $currentAntrian->nomor_antrian }}
                                        </p>
                                        @if($currentAntrian->waktu_panggil)
                                            <div class="inline-flex items-center gap-2 mt-3 text-xs text-gray-600 bg-gray-50 rounded-full px-3 py-1 ring-1 ring-gray-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ $currentAntrian->waktu_panggil->format('H:i:s') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-400">
                                        <p class="text-sm">Belum ada antrian yang dipanggil</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 lg:col-span-2 bg-white rounded-3xl p-16 text-center shadow ring-1 ring-gray-100">
                            <div class="text-6xl mb-4">📋</div>
                            <div class="text-2xl font-bold text-gray-400 mb-2">Belum ada loket yang terdaftar</div>
                            <div class="text-gray-300">Silakan hubungi administrator</div>
                        </div>
                    @endforelse
                </div>

                <!-- Sidebar: riwayat panggilan terakhir -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-3xl shadow-sm ring-1 ring-emerald-100 p-6 h-full">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Terakhir Dipanggil</h3>
                            <span class="text-xs text-gray-400">maks. 10</span>
                        </div>
                        <div class="space-y-3 max-h-[520px] overflow-y-auto pr-1">
                            @forelse($called->take(10) as $item)
                                <div class="flex items-center justify-between rounded-2xl bg-emerald-50/40 ring-1 ring-emerald-100 px-4 py-3">
                                    <div class="min-w-0 pr-4">
                                        <div class="text-[13px] font-medium text-emerald-900 truncate">{{ optional($item->loket)->nama_loket ?? '—' }}</div>
                                        <div class="text-[11px] text-emerald-700/70">{{ optional($item->waktu_panggil)->format('H:i:s') }}</div>
                                    </div>
                                    <div class="text-2xl font-extrabold text-emerald-700">{{ $item->nomor_antrian }}</div>
                                </div>
                            @empty
                                <div class="text-center text-gray-400 py-8">Belum ada panggilan</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer info -->
            <div class="mt-10 text-center text-gray-500 text-xs">
                <p>Perhatikan layar untuk nomor antrian Anda. Terima kasih.</p>
            </div>
        </div>
    </div>
</div>


