<x-layouts.app>
<div class="container mx-auto p-6" wire:poll.2s>
    <h1 class="text-2xl font-bold mb-6">Display Antrian</h1>

    @if(count($called) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($called as $antrian)
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 shadow-lg text-white">
                    <div class="text-center">
                        <div class="text-sm font-medium mb-2 opacity-90">
                            {{ $antrian->loket->nama_loket }}
                        </div>
                        <div class="text-5xl font-bold mb-2">
                            {{ $antrian->loket->code }}{{ $antrian->nomor_antrian }}
                        </div>
                        <div class="text-sm opacity-75">
                            @if($antrian->waktu_panggil)
                                Dipanggil: {{ $antrian->waktu_panggil->format('H:i:s') }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-100 rounded-lg p-12 text-center">
            <div class="text-gray-400 text-xl mb-2">Tidak ada antrian yang sedang dipanggil</div>
            <div class="text-gray-300 text-sm">Menunggu antrian dipanggil...</div>
        </div>
    @endif
</div>
</x-layouts.app>

