<!DOCTYPE html>
<html lang="en" class="h-full overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian - RS Sehat Selalu</title>
    @php
        $viteManifestExists = file_exists(public_path('build/manifest.json'));
        $viteHotExists = file_exists(public_path('hot'));
    @endphp

    @if ($viteManifestExists || $viteHotExists)
        @try
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @catch(\Exception $e)
            <script src="https://cdn.tailwindcss.com"></script>
            <style>
                [x-cloak] { display: none !important; }
            </style>
        @endtry
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            [x-cloak] { display: none !important; }
        </style>
    @endif
    @livewireStyles
</head>
<body class="h-screen w-screen overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900">
    <div class="h-full w-full">
        @yield('content')
    </div>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', function () {
            Livewire.on('error', event => {
                console.error('Livewire Error:', event);
                event.preventDefault();
            });

            Livewire.on('display-updated', ({ timestamp }) => {
                console.log('Display updated at:', new Date(timestamp * 1000));
            });

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
    <script>
        (function setupClock(){
            function pad(n){return n.toString().padStart(2,'0');}
            function tick(){
                var now=new Date();
                var h=pad(now.getHours());
                var m=pad(now.getMinutes());
                var s=pad(now.getSeconds());
                var timeStr=h+":"+m+":"+s;
                var dateStr=now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                var t=document.getElementById('clock-time');
                var d=document.getElementById('clock-date');
                if(t) t.textContent=timeStr;
                if(d) d.textContent=dateStr;
            }
            tick();
            setInterval(tick,1000);
        })();
    </script>
    <style>
        * {
            box-sizing: border-box;
        }
        html, body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse-glow {
            0%, 100% { 
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.6),
                            0 0 60px rgba(99, 102, 241, 0.4);
            }
            50% { 
                box-shadow: 0 0 50px rgba(59, 130, 246, 0.9),
                            0 0 100px rgba(99, 102, 241, 0.6);
            }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        .animate-pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }
        .animate-slide-up {
            animation: slideUp 0.5s ease-out;
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
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
