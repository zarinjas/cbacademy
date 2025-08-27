@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'class' => ''
])

@php
    $baseClasses = 'w-full px-4 py-3 bg-chef-gray-light border border-chef-gray-lighter rounded-2xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-chef-gold-focus focus:border-chef-gold transition-all duration-200';
    $classes = $baseClasses . ' ' . $class;
    
    if ($error) {
        $classes .= ' border-red-500 focus:ring-red-500';
    }
@endphp

<div class="space-y-2">
    @if($label)
        <label class="block text-sm font-medium text-gray-300">
            {{ $label }}
        </label>
    @endif
    
    <input type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    
    @if($error)
        <p class="text-sm text-red-400">{{ $error }}</p>
    @endif
</div>
