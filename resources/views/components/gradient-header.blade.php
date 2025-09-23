@props([
    'title',
    'subtitle' => null,
    'buttonText' => null,
    'buttonAction' => null,
    'buttonIcon' => null,
    'class' => '',
    'showFloatingElements' => true,
])

<div
    class="bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-600 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden {{ $class }}">
    <div class="absolute inset-0 bg-black/10"></div>

    @if ($showFloatingElements)
        <div
            class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32 animate-float-slow">
        </div>
        <div
            class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24 animate-float-medium">
        </div>
        <div
            class="absolute top-1/2 left-1/2 w-32 h-32 bg-white/5 rounded-full -translate-x-16 -translate-y-16 animate-float-fast">
        </div>
    @endif

    <div class="relative flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-4">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-blue-100 text-xl leading-relaxed">{{ $subtitle }}</p>
            @endif
            {{ $slot }}
        </div>

        @if ($buttonText && $buttonAction)
            <div class="flex space-x-4">
                @if ($buttonAction === 'button')
                    <button
                        {{ $attributes->merge(['class' => 'px-6 py-3 bg-white/95 text-blue-600 hover:bg-white font-semibold rounded-xl transition-all duration-300 border border-white/50 shadow-lg hover:shadow-xl hover:scale-105']) }}>
                        <span class="flex items-center">
                            @if ($buttonIcon)
                                <x-icon :name="$buttonIcon" class="h-3 w-3 mr-1" />
                            @endif
                            {{ $buttonText }}
                        </span>
                    </button>
                @elseif($buttonAction === 'link')
                    <a href="{{ $buttonText }}"
                        {{ $attributes->merge(['class' => 'px-6 py-3 bg-white/90 hover:bg-white text-blue-600 font-semibold rounded-xl transition-all duration-300 border border-white/50 shadow-lg hover:shadow-xl hover:scale-105']) }}>
                        <span class="flex items-center">
                            @if ($buttonIcon)
                                <x-icon :name="$buttonIcon" class="h-3 w-3 mr-1" />
                            @endif
                            {{ $buttonText }}
                        </span>
                    </a>
                @else
                    <button onclick="{{ $buttonAction }}"
                        {{ $attributes->merge(['class' => 'px-6 py-3 bg-white/20 backdrop-blur-xl hover:bg-white/30 text-white font-semibold rounded-2xl transition-all duration-300 border border-white/30 hover:border-white/50 shadow-xl hover:shadow-2xl select-none hover:scale-105']) }}>
                        <span class="flex items-center">
                            @if ($buttonIcon)
                                <x-icon :name="$buttonIcon" class="h-3 w-3 mr-1" />
                            @endif
                            {{ $buttonText }}
                        </span>
                    </button>
                @endif
            </div>
        @endif

        {{ $buttons ?? '' }}
    </div>
</div>
