@props([
    'variant' => 'default',
    'size' => 'default',
    'class' => ''
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-full';
    
    $variantClasses = [
        'default' => 'bg-chef-gray-light text-gray-300',
        'success' => 'bg-green-600 text-white',
        'warning' => 'bg-yellow-600 text-white',
        'danger' => 'bg-red-600 text-white',
        'info' => 'bg-blue-600 text-white',
        'gold' => 'bg-chef-gold text-chef-black',
        'gold-outline' => 'bg-transparent border-2 border-chef-gold text-chef-gold',
        'progress' => 'bg-transparent border-2 border-chef-gold text-chef-gold text-xs font-semibold'
    ];
    
    $sizeClasses = [
        'sm' => 'px-2 py-1 text-xs',
        'default' => 'px-3 py-1.5 text-sm',
        'lg' => 'px-4 py-2 text-base'
    ];
    
    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size] . ' ' . $class;
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
