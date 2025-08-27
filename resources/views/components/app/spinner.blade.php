@props([
    'size' => 'default',
    'variant' => 'gold',
    'class' => ''
])

@php
    $baseClasses = 'animate-spin rounded-full border-2 border-transparent';
    
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'default' => 'w-6 h-6',
        'lg' => 'w-8 h-8',
        'xl' => 'w-12 h-12'
    ];
    
    $variantClasses = [
        'gold' => 'border-chef-gold border-t-transparent',
        'white' => 'border-white border-t-transparent',
        'gray' => 'border-gray-400 border-t-transparent'
    ];
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant] . ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}></div>
