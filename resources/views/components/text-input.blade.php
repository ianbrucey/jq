@props(['disabled' => false, 'error' => false])

<input {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge([
        'class' => 'input input-bordered w-full' . ($error ? ' input-error' : '')
    ]) !!}>