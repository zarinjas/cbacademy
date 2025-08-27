@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'default',
    'class' => ''
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-chef-black';
    
    $variantClasses = [
        'primary' => 'bg-chef-gold text-chef-black border-2 border-chef-gold hover:bg-chef-gold-hover hover:border-chef-gold-hover focus:ring-chef-gold-focus shadow-gold-glow',
        'secondary' => 'bg-chef-gray-light text-white border-2 border-chef-gray-lighter hover:bg-chef-gray-lighter hover:border-chef-gold focus:ring-chef-gold-focus',
        'outline' => 'bg-transparent border-2 border-chef-gray-light text-gray-300 hover:border-chef-gold hover:text-chef-gold focus:ring-chef-gold-focus',
        'ghost' => 'bg-transparent text-gray-300 hover:text-chef-gold hover:bg-chef-gray-light focus:ring-chef-gold-focus'
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
