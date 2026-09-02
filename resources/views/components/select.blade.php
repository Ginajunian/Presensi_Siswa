@props(['disabled' => false])

<select @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-brand-400 focus:ring focus:ring-brand-100 focus:ring-opacity-50 rounded-lg shadow-sm text-sm text-gray-700']) }}>
    {{ $slot }}
</select>