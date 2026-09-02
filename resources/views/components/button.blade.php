@props(['variant' => 'primary'])

@php
$variants = [
    'primary' => 'bg-brand-600 hover:bg-brand-700 text-white',
    'secondary' => 'bg-gray-100 hover:bg-gray-200 text-gray-700',
    'danger' => 'bg-rose-600 hover:bg-rose-700 text-white',
];
@endphp

<button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition ' . ($variants[$variant] ?? $variants['primary'])]) }}>
    {{ $slot }}
</button>