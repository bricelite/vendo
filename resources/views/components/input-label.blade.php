@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-texte-secondaire']) }}>
    {{ $value ?? $slot }}
</label>
