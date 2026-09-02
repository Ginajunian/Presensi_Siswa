@props(['value'])

@php
$isNaik = $value >= 0;
@endphp

<span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-medium {{ $isNaik ? 'bg-brand-50 text-brand-700' : 'bg-rose-50 text-rose-700' }}">
    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        @if ($isNaik)
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
        @else
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        @endif
    </svg>
    {{ number_format(abs($value), 1) }}%
</span>