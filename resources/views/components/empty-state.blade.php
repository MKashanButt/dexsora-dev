@props([
    'title',
    'description',
    'buttonText',
    'buttonAction',
    'icon' => 'table',
    'class' => ''
])

<div class="text-center py-16 {{ $class }}" id="emptyState">
    <div class="h-32 w-32 bg-gradient-to-br from-gray-200 via-blue-100 to-indigo-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
        <x-icon name="{{ $icon }}" class="h-16 w-16 text-gray-400" />
    </div>
    <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $title }}</h3>
    <p class="text-gray-600 text-lg mb-8 max-w-lg mx-auto leading-relaxed">{{ $description }}</p>
    
    @if($buttonText && $buttonAction)
        <x-button 
            variant="primary" 
            size="lg" 
            onclick="{{ $buttonAction }}"
            class="inline-flex items-center">
            <x-icon name="plus" class="h-6 w-6 mr-3" />
            {{ $buttonText }}
        </x-button>
    @endif
</div>
