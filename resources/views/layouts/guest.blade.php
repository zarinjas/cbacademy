<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Chef Bull Academy') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-white antialiased bg-chef-black">
        <!-- Toast Messages -->
        @if(session('success'))
            <x-app.toast type="success" :message="session('success')" :show="true" />
        @endif
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-chef-black">
            <div class="text-center mb-3">
                <div class="flex items-center justify-center mb-4">
                    <img src="{{ asset('images/cropped-CB-Academy-Logo-08.png') }}" alt="CB Academy Logo" class="h-20 w-auto object-contain">
                </div>
                
            </div>

            <div class="w-full sm:max-w-md mt-2 px-6 py-8 bg-chef-gray shadow-soft border border-chef-gray-light overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
