@props([
    'placeholder' => 'Search...',
    'oninput' => 'searchTable(this.value)',
    'class' => ''
])

<div class="relative {{ $class }}">
    <input type="text" 
           id="searchInput"
           placeholder="{{ $placeholder }}" 
           class="pl-10 pr-4 py-2 w-56 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 bg-white/80 hover:bg-white text-sm"
           oninput="{{ $oninput }}">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <x-icon name="search" class="h-4 w-4 text-gray-400" />
    </div>
</div>
