@props([
    'type' => 'info',
    'title' => null,
    'message' => null,
    'class' => ''
])

@php
    $typeClasses = [
        'info' => 'bg-blue-600 text-white',
        'success' => 'bg-green-600 text-white',
        'warning' => 'bg-yellow-600 text-white',
        'error' => 'bg-red-600 text-white'
    ];
    
    $classes = 'fixed top-4 right-4 z-50 p-4 rounded-2xl shadow-soft-lg border border-chef-gray-light ' . $typeClasses[$type] . ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($title)
        <div class="font-semibold mb-1">{{ $title }}</div>
    @endif
    
    @if($message)
        <div class="text-sm opacity-90">{{ $message }}</div>
    @endif
    
    {{ $slot }}
</div>
