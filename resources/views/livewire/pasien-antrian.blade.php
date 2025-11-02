<div>
@script
<script>
    document.addEventListener('livewire:initialized', () => {
        // Listen untuk Livewire event 'redirect-to-display'
        Livewire.on('redirect-to-display', () => {
            // Redirect ke halaman display setelah data tersimpan ke database
            // Delay 1.5 detik untuk memastikan commit transaction dan session flash selesai
            setTimeout(() => {
                window.location.href = '{{ route("display.index") }}';
            }, 1500);
        });
    });
</script>
@endscript
<div class="container mx-auto p-6 max-w-4xl">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-center mb-2 text-gray-800">Ambil Nomor Antrian</h1>
        <p class="text-center text-gray-600 mb-8">Pilih loket untuk mengambil nomor antrian</p>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" wire:ignore>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" wire:ignore>
                <p class="font-bold">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded" wire:ignore>
                <p class="font-bold">{{ session('info') }}</p>
            </div>
        @endif

        @error('selected_loket_id')
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p class="font-bold">{{ $message }}</p>
            </div>
        @enderror

        @if($show_success && $antrian_terakhir)
            <div class="bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-400 rounded-lg p-8 mb-8 text-center">
                <div class="mb-4">
                    <svg class="w-20 h-20 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-green-800 mb-4">Nomor Antrian Anda</h2>
                <div class="text-6xl font-bold text-green-700 mb-4">
                    @if(isset($antrian_terakhir) && $antrian_terakhir && $antrian_terakhir->loket)
                        {{ $antrian_terakhir->loket->code ?? '' }}{{ $antrian_terakhir->nomor_antrian ?? 'N/A' }}
                    @elseif(isset($antrian_terakhir) && $antrian_terakhir)
                        {{ $antrian_terakhir->nomor_antrian ?? 'N/A' }}
                    @elseif(isset($nomor_antrian))
                        {{ $nomor_antrian }}
                    @else
                        N/A
                    @endif
                </div>
                @if(isset($antrian_terakhir) && $antrian_terakhir && $antrian_terakhir->loket)
                    <p class="text-lg text-green-700 mb-2">{{ $antrian_terakhir->loket->nama_loket }}</p>
                @endif
                <p class="text-sm text-green-600">Status: <span class="font-bold">Menunggu</span></p>
                <button 
                    wire:click="resetForm"
                    wire:loading.attr="disabled"
                    class="mt-6 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition-colors disabled:opacity-50"
                >
                    Ambil Nomor Antrian Lagi
                </button>
            </div>
        @endif

        @if(!$show_success)
            <form wire:submit.prevent="ambilAntrian" class="space-y-6">
                <div>
                    <label for="loket" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Loket <span class="text-red-500">*</span>
                    </label>
                    <select 
                        wire:model.live="selected_loket_id"
                        id="loket" 
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg @error('selected_loket_id') border-red-500 @enderror"
                        required
                    >
                        <option value="">-- Pilih Loket --</option>
                        @foreach($lokets as $loket)
                            <option value="{{ $loket->id }}">
                                {{ $loket->nama_loket }}
                                @if($loket->code) ({{ $loket->code }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @if($selected_loket_id)
                        @php
                            $loket = $lokets->firstWhere('id', $selected_loket_id);
                        @endphp
                        @if($loket && $loket->deskripsi)
                            <p class="mt-2 text-sm text-gray-600">{{ $loket->deskripsi }}</p>
                        @endif
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if(count($lokets) > 0)
                        @foreach($lokets as $loket)
                            <div 
                                wire:click="$set('selected_loket_id', {{ $loket->id }})"
                                class="border-2 rounded-lg p-4 cursor-pointer transition-all {{ $selected_loket_id == $loket->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300 hover:bg-gray-50' }}"
                            >
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-800">{{ $loket->nama_loket }}</h3>
                                        @if($loket->code)
                                            <span class="text-sm text-gray-600">Code: {{ $loket->code }}</span>
                                        @endif
                                        @if($loket->deskripsi)
                                            <p class="text-sm text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($loket->deskripsi, 50) }}</p>
                                        @endif
                                    </div>
                                    @if($selected_loket_id == $loket->id)
                                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-2 bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <p class="text-yellow-800">Belum ada loket tersedia. Silakan hubungi administrator.</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-center pt-4">
                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="ambilAntrian"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-12 rounded-lg text-lg transition-all shadow-lg hover:shadow-xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 disabled:hover:shadow-lg"
                        @disabled(!$selected_loket_id)
                    >
                        <span wire:loading.remove wire:target="ambilAntrian">
                            Ambil Nomor Antrian
                        </span>
                        <span wire:loading wire:target="ambilAntrian" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
                <!-- Button untuk ambil melalui API (progressive enhancement) -->
                <button
                    type="button"
                    id="api-ambil-btn"
                    onclick="apiAmbilAntrian()"
                    class="ml-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-8 rounded-lg text-lg transition-all shadow-lg hover:shadow-xl transform hover:scale-105 disabled:opacity-50"
                    disabled
                >
                    Ambil via API
                </button>
            </form>
        @endif
    </div>
</div>
    <div id="api-result-container" class="fixed top-6 right-6 z-50" aria-live="polite"></div>

    @script
    <script>
        // Progressive enhancement: helper to call API to create antrian
        function getCsrfToken() {
            const m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.getAttribute('content') : '';
        }

        function showApiResult(message, success = true) {
            const container = document.getElementById('api-result-container');
            const el = document.createElement('div');
            el.className = (success ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 'bg-red-100 border-l-4 border-red-500 text-red-700') + ' p-4 rounded shadow mb-2';
            el.innerText = message;
            container.appendChild(el);
            setTimeout(() => { el.remove(); }, 6000);
        }

        // Enable/disable API button based on selected loket
        document.addEventListener('DOMContentLoaded', () => {
            const loketSelect = document.getElementById('loket');
            const apiBtn = document.getElementById('api-ambil-btn');
            if (!loketSelect || !apiBtn) return;
            loketSelect.addEventListener('change', () => {
                apiBtn.disabled = !loketSelect.value;
            });
            // init state
            apiBtn.disabled = !loketSelect.value;
        });

        async function apiAmbilAntrian() {
            const loketSelect = document.getElementById('loket');
            const loketId = loketSelect ? loketSelect.value : null;
            if (!loketId) {
                showApiResult('Silakan pilih loket terlebih dahulu.', false);
                return;
            }

            try {
                const token = getCsrfToken();
                const res = await fetch(`/api/lokets/${loketId}/antrians`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({})
                });

                const data = await res.json();
                if (res.status === 201) {
                    const nomor = data.data.nomor_antrian || '';
                    const loketNama = data.data.loket ? data.data.loket.nama_loket : '';
                    showApiResult('Berhasil membuat antrian: ' + nomor + ' untuk ' + loketNama, true);

                    // Emit Livewire events so other components refresh
                    if (window.Livewire) {
                        Livewire.emit('refreshDisplay');
                        Livewire.emit('antrian-created', { antrian_id: data.data.id, loket_id: data.data.loket_id });
                        Livewire.emit('refreshList');
                    }
                } else {
                    showApiResult((data.message || 'Gagal membuat antrian') + (data.error ? (': ' + data.error) : ''), false);
                }
            } catch (err) {
                showApiResult('Error saat memanggil API: ' + err.message, false);
            }
        }
    </script>
    @endscript
</div>
