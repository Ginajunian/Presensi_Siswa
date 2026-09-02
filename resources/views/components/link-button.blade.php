@props(['href', 'variant' => 'primary'])

@php
$variants = [
    'primary' => 'bg-brand-600 hover:bg-brand-700 text-white',
    'secondary' => 'bg-gray-100 hover:bg-gray-200 text-gray-700',
    'ghost' => 'text-brand-700 hover:text-brand-800 hover:underline px-0 py-0',
];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium transition ' . ($variants[$variant] ?? $variants['primary'])]) }}>
    {{ $slot }}
</a>