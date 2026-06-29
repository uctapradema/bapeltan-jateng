@props(['href'])

@php
    $active = request()->is(ltrim($href, '/') . '*') ? 'bg-base-200' : 'group-hover:bg-base-200';
@endphp

<span class="relative inline-block group pb-1">
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'relative z-10']) }}>
        {{ $slot }}
    </a>
    <span
        class="absolute left-0 right-0 bottom-0 h-[3px] rounded transition-colors duration-300 {{ $active }}"></span>
</span>
