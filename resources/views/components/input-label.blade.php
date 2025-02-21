@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-base font-semibold']) }}>
    {{ $value ?? $slot }}
</label>
