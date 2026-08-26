@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-succes']) }}>
        {{ $status }}
    </div>
@endif
