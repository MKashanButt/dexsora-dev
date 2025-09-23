@props(['class' => ''])

<div class="bg-gradient-to-r from-gray-50/80 via-blue-50/60 to-indigo-50/60 px-6 py-3 border-b border-gray-200/50 {{ $class }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            {{ $slot }}
        </div>
        <div class="flex items-center space-x-4">
            {{ $actions ?? '' }}
        </div>
    </div>
</div>
