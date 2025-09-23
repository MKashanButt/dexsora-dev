@props(['message', 'class' => ''])

@if($message)
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 animate-slide-up {{ $class }}">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="text-sm font-medium text-green-800">{{ $message }}</span>
        </div>
    </div>
@endif
