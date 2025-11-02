<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
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
            const bridgedEvents = ['antrian-created', 'refreshDisplay', 'refreshList'];

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
        })();
    </script>
</body>
</html>

