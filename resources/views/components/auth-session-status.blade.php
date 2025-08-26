@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-400 bg-green-900/20 border border-green-800 rounded-2xl px-4 py-3']) }}>
        {{ $status }}
    </div>
@endif
