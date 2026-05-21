@props([
    'title' => config('app.name', 'DécisionClaire'),
    'metaDescription' => 'DécisionClaire propose des outils gratuits pour clarifier les décisions d’argent du quotidien.',
    'canonical' => url()->current(),
    'robots' => 'index, follow',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="robots" content="{{ $robots }}">
        <link rel="canonical" href="{{ $canonical }}">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="fr_FR">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $canonical }}">
        <meta name="twitter:card" content="summary">

        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900">
        <div class="dc-app-bg min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="border-b border-white/70 bg-white/70 backdrop-blur">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
