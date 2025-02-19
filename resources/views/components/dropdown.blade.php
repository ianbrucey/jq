@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1', 'dropdownClasses' => ''])

@php
$alignmentClasses = match ($align) {
    'left' => 'dropdown-start',
    'right' => 'dropdown-end',
    'top' => 'dropdown-top',
    default => 'dropdown-end',
};

$width = match ($width) {
    '48' => 'w-48',
    '60' => 'w-60',
    default => 'w-48',
};
@endphp

<div class="dropdown {{ $alignmentClasses }}" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         class="dropdown-content menu p-2 shadow bg-base-100 rounded-box {{ $width }} {{ $contentClasses }} {{ $dropdownClasses }}"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95">
        {{ $content }}
    </div>
</div>
