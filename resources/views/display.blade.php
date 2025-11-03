<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Display Antrian - RS Sehat Selalu</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    {{ $slot }}

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', function () {
            // Set up error handler to prevent white screen
            Livewire.on('error', event => {
                console.error('Livewire Error:', event);
                event.preventDefault();
            });

            // Handle display updates
            Livewire.on('display-updated', ({ timestamp }) => {
                console.log('Display updated at:', new Date(timestamp * 1000));
            });

            // Optional: Add loading indicator
            let loadingTimeout;
            
            Livewire.hook('commit.prepare', () => {
                loadingTimeout = setTimeout(() => {
                    document.body.classList.add('loading');
                }, 500);
            });
            
            Livewire.hook('commit.finished', () => {
                clearTimeout(loadingTimeout);
                document.body.classList.remove('loading');
            });
        });
    </script>
    <style>
        .loading {
            opacity: 0.8;
            transition: opacity 0.3s ease-in-out;
        }
        .loading * {
            pointer-events: none;
        }
        [wire\:loading] {
            display: none;
        }
        [wire\:loading].show {
            display: block;
        }
        [wire\:poll] {
            transition: opacity 0.3s ease-in-out;
        }
    </style>
</body>
</html>