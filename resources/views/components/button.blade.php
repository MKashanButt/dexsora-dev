@props([
    'variant' => 'primary', // primary, secondary, danger, success, warning
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'href' => null,
    'onclick' => null,
    'class' => '',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-300';

    // Size classes
    $sizeClasses = [
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-3 text-sm',
        'lg' => 'px-6 py-4 text-base',
    ];

    // Variant classes
    $variantClasses = [
        'primary' =>
            'bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 text-white shadow-lg hover:shadow-xl hover:scale-105 hover:from-blue-600 hover:via-purple-600 hover:to-indigo-700',
        'secondary' =>
            'bg-white/80 text-gray-700 border border-gray-200/50 hover:bg-white hover:text-gray-900 hover:shadow-md hover:scale-105',
        'danger' => 'bg-red-500 text-white shadow-lg hover:shadow-xl hover:scale-105 hover:bg-red-600',
        'success' => 'bg-green-500 text-white shadow-lg hover:shadow-xl hover:scale-105 hover:bg-green-600',
        'warning' => 'bg-yellow-500 text-white shadow-lg hover:shadow-xl hover:scale-105 hover:bg-yellow-600',
        'outline' =>
            'border border-gray-300 text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50 hover:text-blue-700 hover:shadow-md hover:scale-105',
    ];

    $allClasses = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant] . ' ' . $class;
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $allClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $allClasses]) }}
        @if ($onclick) onclick="{{ $onclick }}" @endif>
        {{ $slot }}
    </button>
@endif
