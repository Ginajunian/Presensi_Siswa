<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Presensi Siswa') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4 py-10">

        <a href="{{ route('welcome') }}" class="flex items-center gap-2 mb-6">
            <x-application-logo class="w-11 h-11" />
            <span class="font-semibold text-gray-800 text-lg">{{ config('app.name', 'Presensi Siswa') }}</span>
        </a>

        <div class="w-full sm:max-w-md bg-white rounded-2xl border border-gray-100 shadow-sm shadow-gray-100 px-6 py-8">
            {{ $slot }}
        </div>

    </div>
</body>
</html>