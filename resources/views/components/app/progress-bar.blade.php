@props([
    'value' => 0,
    'max' => 100,
    'size' => 'default',
    'variant' => 'gold',
    'showLabel' => true,
    'animated' => true,
    'class' => ''
])

@php
    $percentage = min(100, max(0, ($value / $max) * 100));
    
    $sizeClasses = [
        'sm' => 'h-2',
        'default' => 'h-3',
        'lg' => 'h-4',
        'xl' => 'h-6'
    ];
    
    $variantClasses = [
        'gold' => 'bg-chef-gold',
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
        'red' => 'bg-red-500'
    ];
    
    $labelClasses = [
        'sm' => 'text-xs',
        'default' => 'text-sm',
        'lg' => 'text-base',
        'xl' => 'text-lg'
    ];
    
    $classes = $sizeClasses[$size] . ' ' . $class;
@endphp

<div class="space-y-2">
    @if($showLabel)
        <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-300">Progress</span>
            <span class="text-sm font-medium text-chef-gold">{{ round($percentage, 1) }}%</span>
        </div>
    @endif
    
    <div class="w-full bg-chef-gray-light rounded-full overflow-hidden {{ $classes }}">
        <div 
            class="{{ $variantClasses[$variant] }} h-full rounded-full transition-all duration-1000 ease-out {{ $animated ? 'animate-pulse-slow' : '' }}"
            style="width: {{ $percentage }}%"
            x-data="{ width: 0 }"
            x-init="
                $nextTick(() => {
                    width = {{ $percentage }};
                })
            "
            :style="`width: ${width}%`"
        ></div>
    </div>
    
    @if($showLabel && $max > 1)
        <div class="flex items-center justify-between text-xs text-gray-400">
            <span>{{ $value }} of {{ $max }} completed</span>
            <span>{{ $max - $value }} remaining</span>
        </div>
    @endif
</div>
