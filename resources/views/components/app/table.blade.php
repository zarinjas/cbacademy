@props(['class' => ''])

<div class="overflow-x-auto rounded-2xl border border-chef-gray-lighter bg-chef-gray">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-chef-gray-lighter ' . $class]) }}>
        {{ $slot }}
    </table>
</div>
