<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    />

    {{-- Emergency Railway CSS/JS force load --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-ByupHFNO.css') }}">
    <script type="module" src="{{ asset('build/assets/app-BD36QnPI.js') }}"></script>

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col">

        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white border-b border-gray-100 shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1">
            {{ $slot }}
        </main>

        <footer class="bg-white border-t border-gray-200 mt-10">
            <div class="max-w-7xl mx-auto px-6 py-10">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-green-700 text-white flex items-center justify-center font-bold text-xl shadow">
                                C
                            </div>

                            <div>
                                <h2 class="text-xl font-bold text-gray-900">
                                    Clean-Up System
                                </h2>

                                <p class="text-sm text-gray-500">
                                    Community Reporting Platform
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 leading-relaxed">
                            A community-driven platform for reporting environmental concerns,
                            waste management issues, and clean-up activities.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Quick Links
                        </h3>

                        <ul class="space-y-3 text-sm">
                            <li>
                                <a href="/" class="text-gray-600 hover:text-green-700 transition">
                                    Home
                                </a>
                            </li>

                            <li>
                                <a href="/report" class="text-gray-600 hover:text-green-700 transition">
                                    Submit Report
                                </a>
                            </li>

                            @auth
                                <li>
                                    <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-green-700 transition">
                                        Messages
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-green-700 transition">
                                        User Settings
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Contact
                        </h3>

                        <ul class="space-y-3 text-sm text-gray-600">
                            <li>📍 Cagayan de Oro City</li>
                            <li>📧 support@cleanupsystem.com</li>
                            <li>📞 +63 912 345 6789</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            About System
                        </h3>

                        <p class="text-sm text-gray-600 leading-relaxed">
                            Designed to improve environmental reporting,
                            team coordination, and clean-up monitoring
                            for local communities.
                        </p>
                    </div>

                </div>

                <div class="border-t border-gray-200 mt-10 pt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <p class="text-sm text-gray-500">
                        © {{ date('Y') }} Clean-Up Reporting System. All rights reserved.
                    </p>

                    <p class="text-sm text-gray-500">
                        Developed by
                        <span class="font-semibold text-green-700">
                            Shem Japheth Panes
                        </span>
                    </p>
                </div>
            </div>
        </footer>

    </div>
</body>

</html>