@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-fond-alterne focus:border-principale focus:ring-principale rounded-xl shadow-sm bg-white/60 backdrop-blur-sm']) }}>
