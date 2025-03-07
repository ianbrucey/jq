@props(['disabled' => false])

<input type="radio" {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'radio radio-primary'
    ]) !!}>
