@props(['color' => 'gray'])

@php
$colors = [
    'green' => 'bg-brand-50 text-brand-700',
    'yellow' => 'bg-amber-50 text-amber-700',
    'red' => 'bg-rose-50 text-rose-700',
    'gray' => 'bg-gray-100 text-gray-600',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ' . ($colors[$color] ?? $colors['gray'])]) }}>
    {{ $slot }}
</span>