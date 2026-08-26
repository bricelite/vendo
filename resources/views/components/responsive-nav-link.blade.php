@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-principale text-start text-base font-medium text-principale bg-principale/10 focus:outline-none focus:text-principale focus:bg-principale/15 focus:border-principale transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-texte-secondaire hover:text-texte hover:bg-fond-alterne hover:border-fond-alterne focus:outline-none focus:text-texte focus:bg-fond-alterne focus:border-fond-alterne transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
