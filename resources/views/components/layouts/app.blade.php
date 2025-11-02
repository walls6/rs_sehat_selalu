<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<<<<<<< HEAD
    <meta name="csrf-token" content="{{ csrf_token() }}">
=======
>>>>>>> a35650fe089b9eeded013ae0f9469ed4217c8243
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
<<<<<<< HEAD
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @php
        $viteManifestExists = file_exists(public_path('build/manifest.json'));
        $viteHotExists = file_exists(public_path('hot'));
    @endphp
    
    @if ($viteManifestExists || $viteHotExists)
        @try
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @catch(\Exception $e)
            <!-- Fallback jika Vite error -->
            <script src="https://cdn.tailwindcss.com"></script>
            <style>
                [x-cloak] { display: none !important; }
            </style>
        @endtry
    @else
        <!-- Fallback styles jika Vite belum di-build -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            [x-cloak] { display: none !important; }
        </style>
    @endif
    
    @livewireStyles
</head>
<body class="antialiased bg-gray-50">
    {{ $slot }}
    
    @livewireScripts
=======
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="antialiased">
    {{ $slot }}
    
    @livewireScripts
    <script>
        // Livewire bridge: forward browser-dispatched events (dispatchBrowserEvent)
        // to Livewire.emit so other Livewire components listening for emits still get notified.
        (function() {
            // List of events we want to bridge. Add more names here if needed.
            const bridgedEvents = ['antrian-created', 'refreshDisplay', 'refreshList', 'antrian-dipanggil'];

            function forward(eventName) {
                window.addEventListener(eventName, function(e) {
                    try {
                        if (window.Livewire && typeof Livewire.emit === 'function') {
                            // If event has detail, forward it, otherwise pass no payload
                            Livewire.emit(eventName, e && e.detail ? e.detail : undefined);
                        }
                    } catch (err) {
                        // Non-fatal: Livewire may not be initialized yet in some contexts
                        // Log to console for debugging but do not break the page.
                        console.warn('Livewire bridge: failed to emit', eventName, err);
                    }
                });
            }

            for (const ev of bridgedEvents) {
                forward(ev);
            }

            // Play a short beep when a new antrian is created to alert petugas/display
            // Default is relatively soft; callers can pass stronger params for louder alerts.
            function beep(duration = 120, frequency = 880, volume = 0.05, type = 'sine') {
                try {
                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                    const ctx = new AudioContext();
                    const o = ctx.createOscillator();
                    const g = ctx.createGain();
                    o.type = type;
                    o.frequency.value = frequency;
                    g.gain.value = volume;
                    o.connect(g);
                    g.connect(ctx.destination);
                    o.start(0);
                    setTimeout(function() { o.stop(); ctx.close(); }, duration);
                } catch (e) {
                    // ignore audio errors silently
                    console.warn('Beep failed', e);
                }
            }
            // Visual highlight + audio when antrian is called or created
            function handleAntrianCreated(detail) {
                try {
                    // softer ping for create
                    beep(120, 880, 0.06, 'sine');
                    console.info('antrian-created', detail || null);
                } catch (err) {
                    console.warn('antrian-created beep failed', err);
                }
            }

            function handleAntrianDipanggil(detail) {
                try {
                    // stronger, longer beep for call
                    beep(350, 1000, 0.14, 'sine');
                    console.info('antrian-dipanggil', detail || null);

                    // Add a quick highlight to the called-panel element if present
                    const el = document.getElementById('called-panel');
                    if (el) {
                        el.classList.add('ring-4', 'ring-blue-300');
                        // remove highlight after a moment
                        setTimeout(() => {
                            el.classList.remove('ring-4', 'ring-blue-300');
                        }, 900);
                    }
                } catch (err) {
                    console.warn('antrian-dipanggil handler failed', err);
                }
            }

            window.addEventListener('antrian-created', function(e) { handleAntrianCreated(e && e.detail ? e.detail : undefined); });
            window.addEventListener('antrian-dipanggil', function(e) { handleAntrianDipanggil(e && e.detail ? e.detail : undefined); });

            // Also listen for Livewire client-side events (emitted by server-to-client Livewire)
            if (window.Livewire && typeof Livewire.on === 'function') {
                Livewire.on('antrian-dipanggil', function(detail) { handleAntrianDipanggil(detail); });
                Livewire.on('antrian-created', function(detail) { handleAntrianCreated(detail); });
            }
        })();
    </script>
>>>>>>> a35650fe089b9eeded013ae0f9469ed4217c8243
</body>
</html>

