@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-principale text-sm font-medium leading-5 text-texte focus:outline-none focus:border-principale transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-texte-secondaire hover:text-texte hover:border-fond-alterne focus:outline-none focus:text-texte focus:border-fond-alterne transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
