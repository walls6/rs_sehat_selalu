<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
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
    @stack('styles')
</head>
<body class="antialiased">
    {{ $slot }}
    
    @livewireScripts
    @stack('scripts')
</body>
</html>

