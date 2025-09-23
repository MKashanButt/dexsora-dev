@props([
    'class' => '',
    'hover' => true,
    'clickable' => false,
    'onclick' => null
])

@php
    $baseClasses = 'bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl border border-white/20';
    $hoverClasses = $hover ? 'hover:shadow-2xl transition-all duration-300' : '';
    $clickableClasses = $clickable ? 'cursor-pointer transform hover:-translate-y-2' : '';
    $allClasses = $baseClasses . ' ' . $hoverClasses . ' ' . $clickableClasses . ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $allClasses]) }} @if($onclick) onclick="{{ $onclick }}" @endif>
    {{ $slot }}
</div>
