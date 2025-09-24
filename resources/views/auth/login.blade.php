<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - DexSora</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    @if (config('app.env') === 'production')
        <script src="{{asset('js/tailwind.min.js')}}"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="font-sans antialiased bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <!-- Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div
                class="absolute top-0 left-0 w-96 h-96 bg-blue-200/20 rounded-full -translate-x-48 -translate-y-48 animate-float-slow">
            </div>
            <div
                class="absolute bottom-0 right-0 w-96 h-96 bg-purple-200/20 rounded-full translate-x-48 translate-y-48 animate-float-medium">
            </div>
            <div
                class="absolute top-1/2 left-1/2 w-64 h-64 bg-indigo-200/10 rounded-full -translate-x-32 -translate-y-32 animate-float-fast">
            </div>

            <!-- Floating Particles -->
            <div class="absolute top-20 right-20 w-4 h-4 bg-blue-400/30 rounded-full animate-pulse"></div>
            <div class="absolute top-40 left-1/4 w-3 h-3 bg-purple-400/40 rounded-full animate-pulse"
                style="animation-delay: 1s;"></div>
            <div class="absolute bottom-40 right-1/3 w-2 h-2 bg-indigo-400/50 rounded-full animate-pulse"
                style="animation-delay: 2s;"></div>

            <!-- Subtle Grid -->
            <div
                class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.02)_1px,transparent_1px)] bg-[size:100px_100px]">
            </div>
        </div>

        <div class="max-w-md w-full space-y-8 relative z-10">
            <div>
                <div
                    class="mx-auto h-20 w-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 via-purple-500 to-indigo-600 shadow-2xl">
                    <h1 class="text-3xl font-bold text-white">D</h1>
                </div>
                <h2 class="mt-8 text-center text-4xl font-bold text-gray-900">
                    Welcome back! 👋
                </h2>
                <p class="mt-3 text-center text-lg text-gray-600">
                    Sign in to your DexSora workspace
                </p>
            </div>

            <form class="mt-10 space-y-8" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-3">Email address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="w-full px-6 py-4 border border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/80 backdrop-blur-sm hover:bg-white"
                            placeholder="Enter your email" value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-3">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="w-full px-6 py-4 border border-gray-200 rounded-2xl placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white/80 backdrop-blur-sm hover:bg-white"
                            placeholder="Enter your password">
                    </div>
                </div>

                @if ($errors->any())
                    <div class="rounded-2xl bg-red-50/80 backdrop-blur-sm border border-red-200 p-6">
                        <div class="text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-2">
                                @foreach ($errors->all() as $error)
                                    <li class="font-medium">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-4 px-6 border border-transparent text-lg font-semibold rounded-2xl text-white bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 hover:from-blue-700 hover:via-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-6">
                            <svg class="h-6 w-6 text-blue-300 group-hover:text-blue-200"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        Sign in to DexSora
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-base text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                            class="font-semibold text-blue-600 hover:text-blue-700 transition-colors duration-300">
                            Sign up here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>