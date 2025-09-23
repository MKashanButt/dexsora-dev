@props(['columns', 'rows', 'status' => 'Real-time editing', 'class' => ''])

<div class="bg-gradient-to-r from-gray-50/80 via-blue-50/60 to-indigo-50/60 px-6 py-3 border-t border-gray-200/50 {{ $class }}">
    <div class="flex items-center justify-between text-sm text-gray-600">
        <div class="flex items-center space-x-3">
            <span class="flex items-center space-x-2">
                <x-icon name="table" class="h-4 w-4 text-blue-500" />
                <span>{{ $columns }} columns</span>
            </span>
            <span class="flex items-center space-x-2">
                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18"/><path d="M3 12h18"/>
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                </svg>
                <span>{{ $rows }} rows</span>
            </span>
        </div>
        <div class="flex items-center space-x-2">
            <div class="h-2 w-2 bg-green-400 rounded-full animate-pulse"></div>
            <span>{{ $status }}</span>
        </div>
    </div>
</div>
