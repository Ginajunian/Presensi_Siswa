@props(['color' => 'gray', 'label'])

@php
$colors = [
    'gray' => 'bg-gray-100 hover:bg-gray-200 text-gray-600',
    'amber' => 'bg-amber-50 hover:bg-amber-100 text-amber-600',
    'rose' => 'bg-rose-50 hover:bg-rose-100 text-rose-600',
];
@endphp

<button type="submit" title="{{ $label }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center w-8 h-8 rounded-lg transition ' . ($colors[$color] ?? $colors['gray'])]) }}>
    {{ $slot }}
    <span class="sr-only">{{ $label }}</span>
</button>