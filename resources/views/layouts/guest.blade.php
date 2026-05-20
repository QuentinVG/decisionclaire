<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DécisionClaire') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="dc-app-bg flex min-h-screen flex-col items-center justify-center px-4 py-8">
            <a href="/" class="mb-6 flex items-center gap-3">
                <x-application-logo class="h-12 w-12" />
                <span>
                    <span class="block text-lg font-extrabold text-slate-950">DécisionClaire</span>
                    <span class="text-sm font-semibold text-emerald-800">Décider sans pression</span>
                </span>
            </a>

            <div class="dc-surface w-full max-w-md p-6">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
