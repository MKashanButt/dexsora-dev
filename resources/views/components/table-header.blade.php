@props(['title', 'subtitle', 'icon' => 'table', 'class' => ''])

<div class="bg-gradient-to-r from-gray-50/90 via-blue-50/70 to-indigo-50/70 px-6 py-4 border-b border-gray-200/50 {{ $class }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="h-8 w-8 bg-gradient-to-br from-blue-500 via-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <x-icon name="{{ $icon }}" class="h-4 w-4 text-white" />
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">{{ $title }}</h3>
                @if($subtitle)
                    <p class="text-sm text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
            <span>Auto-save enabled</span>
        </div>
    </div>
</div>
