@props(['class' => ''])

<div class="absolute inset-0 overflow-hidden pointer-events-none {{ $class }}">
    <!-- Floating Particles -->
    <div class="absolute top-20 right-20 w-4 h-4 bg-blue-400/30 rounded-full animate-pulse"></div>
    <div class="absolute top-40 left-1/4 w-3 h-3 bg-purple-400/40 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-40 right-1/3 w-2 h-2 bg-indigo-400/50 rounded-full animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/2 left-20 w-3 h-3 bg-blue-400/30 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
    
    <!-- Subtle Grid -->
    <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.02)_1px,transparent_1px)] bg-[size:100px_100px]"></div>
</div>
