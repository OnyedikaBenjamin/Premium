@props(['value'])

<label {{ $attributes->merge(['class' => '']) }}>
    {{ $value ?? $slot }}
</label>
