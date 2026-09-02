@props(['href', 'color' => 'gray', 'label'])

@php
$colors = [
    'gray' => 'bg-gray-100 hover:bg-gray-200 text-gray-600',
    'green' => 'bg-brand-50 hover:bg-brand-100 text-brand-700',
];
@endphp

<a href="{{ $href }}" title="{{ $label }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center w-8 h-8 rounded-lg transition ' . ($colors[$color] ?? $colors['gray'])]) }}>
    {{ $slot }}
    <span class="sr-only">{{ $label }}</span>
</a>