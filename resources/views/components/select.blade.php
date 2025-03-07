@props(['disabled' => false, 'error' => false])

<select {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'select select-bordered w-full' . ($error ? ' select-error' : '')
    ]) !!}>
    {{ $slot }}
</select>
