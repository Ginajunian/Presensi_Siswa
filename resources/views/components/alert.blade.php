@props(['type' => 'success'])

@php
$styles = [
    'success' => 'bg-brand-50 text-brand-700 border-brand-100',
    'error' => 'bg-rose-50 text-rose-700 border-rose-100',
];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border px-4 py-3 text-sm mb-4 ' . ($styles[$type] ?? $styles['success'])]) }}>
    {{ $slot }}
</div>