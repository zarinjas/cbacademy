<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ChefBull Academy') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-white antialiased bg-chef-black">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-chef-black">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center space-x-3 mb-4">
                    <div class="w-16 h-16 bg-chef-gold rounded-2xl flex items-center justify-center">
                        <span class="text-chef-black font-bold text-2xl">C</span>
                    </div>
                    <span class="text-3xl font-bold text-chef-gold">chefbullacademy</span>
                </div>
                <p class="text-gray-400 text-lg">Learning Management System</p>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-chef-gray shadow-soft border border-chef-gray-light overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
