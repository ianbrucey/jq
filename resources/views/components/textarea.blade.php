@props(['disabled' => false, 'error' => false])

<textarea {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'textarea textarea-bordered w-full' . ($error ? ' textarea-error' : '')
    ]) !!}>{{ $slot }}</textarea>
