@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-principale focus:ring-principale rounded-lg shadow-sm']) }}>
