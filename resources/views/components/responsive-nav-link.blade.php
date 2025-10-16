@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-chef-gold text-start text-base font-medium text-white bg-chef-gray-light/20 focus:outline-none focus:text-white focus:bg-chef-gray-light/30 focus:border-chef-gold transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-300 hover:text-white hover:bg-chef-gray-light/10 hover:border-chef-gray-light focus:outline-none focus:text-white focus:bg-chef-gray-light/10 focus:border-chef-gray-light transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
