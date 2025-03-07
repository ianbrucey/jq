@props([
    'type' => 'button',
    'color' => 'primary',
    'size' => 'md',
    'disabled' => false
])

@php
    $baseClass = 'btn';
    $colorClass = match($color) {
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'accent' => 'btn-accent',
        'ghost' => 'btn-ghost',
        default => 'btn-primary'
    };
    $sizeClass = match($size) {
        'xs' => 'btn-xs',
        'sm' => 'btn-sm',
        'md' => '',
        'lg' => 'btn-lg',
        default => ''
    };
@endphp

<button
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => "{$baseClass} {$colorClass} {$sizeClass}"
    ]) !!}
>
    {{ $slot }}
</button>
