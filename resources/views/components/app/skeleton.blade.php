@props([
    'type' => 'card',
    'lines' => 3,
    'class' => ''
])

@php
    $baseClasses = 'animate-pulse';
    
    $typeClasses = [
        'card' => 'bg-chef-gray-light rounded-2xl p-6',
        'text' => 'bg-chef-gray-light rounded h-4',
        'avatar' => 'bg-chef-gray-light rounded-full',
        'button' => 'bg-chef-gray-light rounded-2xl h-12',
        'image' => 'bg-chef-gray-light rounded-2xl w-full h-32'
    ];
    
    $classes = $baseClasses . ' ' . $typeClasses[$type] . ' ' . $class;
@endphp

@if($type === 'text')
    <div class="space-y-3">
        @for($i = 0; $i < $lines; $i++)
            <div class="{{ $classes }} {{ $i === 0 ? 'w-3/4' : ($i === 1 ? 'w-1/2' : 'w-2/3') }}"></div>
        @endfor
    </div>
@elseif($type === 'card')
    <div class="{{ $classes }}">
        <div class="space-y-4">
            <div class="bg-chef-gray-lighter rounded h-6 w-3/4"></div>
            <div class="space-y-2">
                <div class="bg-chef-gray-lighter rounded h-4 w-full"></div>
                <div class="bg-chef-gray-lighter rounded h-4 w-2/3"></div>
                <div class="bg-chef-gray-lighter rounded h-4 w-1/2"></div>
            </div>
        </div>
    </div>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}></div>
@endif
