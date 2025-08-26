@props(['class' => ''])

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-chef-gray-light ' . $class]) }}>
        {{ $slot }}
    </table>
</div>
