<x-layouts.app>
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard Petugas Loket</h1>

    <div class="mb-6">
        <label for="loket" class="block text-sm font-medium text-gray-700 mb-2">Pilih Loket:</label>
        <select 
            wire:model.live="loket_id" 
            id="loket" 
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
        >
            <option value="">-- Pilih Loket --</option>
            @foreach($lokets as $loket)
                <option value="{{ $loket->id }}">{{ $loket->nama_loket }} @if($loket->code)({{ $loket->code }})@endif</option>
            @endforeach
        </select>
    </div>

    @if($loket_id)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Antrian yang Dipanggil -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-4 text-blue-800">Sedang Dipanggil</h2>
                @if($called)
                    <div class="bg-white rounded-lg p-6 shadow-md">
                        <div class="text-3xl font-bold text-blue-600 mb-2">
                            {{ $called->loket->code }}{{ $called->nomor_antrian }}
                        </div>
                        <div class="text-sm text-gray-600">
                            Loket: {{ $called->loket->nama_loket }}
                        </div>
                        <div class="text-sm text-gray-500 mt-2">
                            Dipanggil: {{ $called->waktu_panggil ? $called->waktu_panggil->format('H:i:s') : '-' }}
                        </div>
                        <button 
                            wire:click="finish({{ $called->id }})"
                            class="mt-4 w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
                        >
                            Selesai
                        </button>
                    </div>
                @else
                    <div class="text-gray-500 text-center py-8">Tidak ada antrian yang sedang dipanggil</div>
                @endif
            </div>

            <!-- Daftar Antrian Menunggu -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h2 class="text-xl font-semibold mb-4 text-yellow-800">Antrian Menunggu</h2>
                @if(count($waiting) > 0)
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($waiting as $antrian)
                            <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-xl font-bold text-gray-800">
                                            {{ $antrian->loket->code }}{{ $antrian->nomor_antrian }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Didaftar: {{ $antrian->created_at->format('H:i:s') }}
                                        </div>
                                    </div>
                                    <button 
                                        wire:click="callNow({{ $antrian->id }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded text-sm"
                                    >
                                        Panggil
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-gray-500 text-center py-8">Tidak ada antrian menunggu</div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-gray-100 rounded-lg p-8 text-center text-gray-500">
            Silakan pilih loket untuk melihat antrian
        </div>
    @endif
</div>
</x-layouts.app>

