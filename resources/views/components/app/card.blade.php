@props(['class' => '', 'hover' => false])

<div {{ $attributes->merge(['class' => 'bg-chef-gray border border-chef-gray-lighter rounded-2xl p-6 shadow-card ' . ($hover ? 'hover:shadow-card-hover transition-shadow duration-300' : '') . ' ' . $class]) }}>
    {{ $slot }}
</div>
