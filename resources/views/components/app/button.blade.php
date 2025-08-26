@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'default',
    'class' => ''
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black';
    
    $variantClasses = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'outline' => 'bg-transparent border-2 border-chef-gray-light text-gray-300 hover:border-chef-gold hover:text-chef-gold',
        'ghost' => 'bg-transparent text-gray-300 hover:text-chef-gold hover:bg-chef-gray-light'
    ];
    
    $sizeClasses = [
        'sm' => 'px-4 py-2 text-sm rounded-xl',
        'default' => 'px-6 py-3 rounded-2xl',
        'lg' => 'px-8 py-4 text-lg rounded-3xl'
    ];
    
    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size] . ' ' . $class;
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
