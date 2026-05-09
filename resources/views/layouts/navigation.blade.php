<nav x-data="{ open: false }"
     class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between h-16">

            {{-- LEFT --}}
            <div class="flex items-center gap-10">

                {{-- Logo --}}
                <a href="/"
                   class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-green-700 text-white flex items-center justify-center font-bold text-lg shadow">
                        C
                    </div>

                    <div>
                        <h1 class="font-bold text-gray-900 leading-tight">
                            Clean-Up System
                        </h1>

                        <p class="text-xs text-gray-500">
                            Community Reporting
                        </p>
                    </div>

                </a>

                {{-- Desktop Links --}}
                <div class="hidden md:flex items-center gap-2">

                    <a href="/"
                       class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                        Home
                    </a>

                    <a href="/report"
                       class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                        Submit Report
                    </a>

                    @auth

                        <a href="{{ route('messages.index') }}"
                           class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Messages
                        </a>

                    @endauth

                    @auth

                        @if(auth()->user()->role === 'admin')

                            <a href="/admin/reports"
                               class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                                Admin
                            </a>

                            <a href="/admin/teams"
                               class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                                Teams
                            </a>

                        @endif

                        @if(auth()->user()->role === 'personnel')

                            <a href="/personnel"
                               class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                                Personnel
                            </a>

                        @endif

                    @endauth

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="hidden md:flex items-center gap-4">

                @guest

                    <a href="/login"
                       class="px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                        Login
                    </a>

                    <a href="/register"
                       class="px-5 py-2 bg-green-700 text-white rounded-xl text-sm font-semibold hover:bg-green-800 transition">
                        Register
                    </a>

                @else

                    {{-- Notifications --}}
                    <x-dropdown align="right" width="80">

                        <x-slot name="trigger">

                            <button class="relative p-3 rounded-2xl hover:bg-gray-100 transition">

                                {{-- Bell Icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-gray-700"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118
                                        14.158V11a6.002 6.002 0 00-4-5.659V5a2
                                        2 0 10-4 0v.341C7.67 6.165 6 8.388 6
                                        11v3.159c0 .538-.214 1.055-.595
                                        1.436L4 17h5m6 0v1a3 3 0 11-6
                                        0v-1m6 0H9"/>

                                </svg>

                                {{-- Unread Count --}}
                                @if(auth()->user()->unreadNotifications->count())

                                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">

                                        {{ auth()->user()->unreadNotifications->count() }}

                                    </span>

                                @endif

                            </button>

                        </x-slot>

                        <x-slot name="content">

                            <div class="w-80">

                                <div class="px-4 py-3 border-b border-gray-100">

                                    <h3 class="font-bold text-gray-900">
                                        Notifications
                                    </h3>

                                </div>

                                <div class="max-h-96 overflow-y-auto">

                                    @forelse(auth()->user()->notifications->take(10) as $notification)

                                        <div class="px-4 py-4 border-b border-gray-100
                                            {{ $notification->read_at ? 'bg-white' : 'bg-green-50' }}">

                                            <div class="flex items-start gap-3">

                                                <div class="w-10 h-10 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">
                                                    🔔
                                                </div>

                                                <div class="flex-1">

                                                    <p class="font-semibold text-gray-900 text-sm">

                                                        {{ $notification->data['title'] ?? 'Notification' }}

                                                    </p>

                                                    <p class="text-sm text-gray-600 mt-1">

                                                        {{ $notification->data['message'] ?? '' }}

                                                    </p>

                                                    <p class="text-xs text-gray-400 mt-2">

                                                        {{ $notification->created_at->diffForHumans() }}

                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    @empty

                                        <div class="px-4 py-6 text-center text-gray-500">

                                            No notifications yet.

                                        </div>

                                    @endforelse

                                </div>

                            </div>

                        </x-slot>

                    </x-dropdown>

                    {{-- User Dropdown --}}
                    <x-dropdown align="right" width="60">

                        <x-slot name="trigger">

                            <button class="flex items-center gap-3 px-3 py-2 rounded-2xl hover:bg-gray-100 transition">

                                {{-- Avatar --}}
                                @if(Auth::user()->profile_photo)

                                    <img
                                        src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                        class="w-11 h-11 rounded-full object-cover border-2 border-white shadow"
                                    >

                                @else

                                    <div class="w-11 h-11 rounded-full bg-green-700 text-white flex items-center justify-center font-bold shadow">

                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                                    </div>

                                @endif

                                {{-- User Info --}}
                                <div class="text-left">

                                    <p class="font-semibold text-gray-900 text-sm">
                                        {{ Auth::user()->name }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ ucfirst(Auth::user()->role) }}
                                    </p>

                                </div>

                                {{-- Arrow --}}
                                <svg class="w-4 h-4 text-gray-500"
                                     xmlns="http://www.w3.org/2000/svg"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M19 9l-7 7-7-7" />

                                </svg>

                            </button>

                        </x-slot>

                        <x-slot name="content">

                            <div class="px-4 py-3 border-b border-gray-100">

                                <p class="font-semibold text-gray-900">
                                    {{ Auth::user()->name }}
                                </p>

                                <p class="text-sm text-gray-500">
                                    {{ Auth::user()->email }}
                                </p>

                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                User Settings
                            </x-dropdown-link>

                            <form method="POST"
                                  action="{{ route('logout') }}">

                                @csrf

                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault();
                                             this.closest('form').submit();">

                                    Logout

                                </x-dropdown-link>

                            </form>

                        </x-slot>

                    </x-dropdown>

                @endguest

            </div>

            {{-- Mobile Button --}}
            <div class="flex items-center md:hidden">

                <button
                    @click="open = ! open"
                    class="p-2 rounded-xl hover:bg-gray-100 transition"
                >

                    <svg class="h-6 w-6 text-gray-700"
                         stroke="currentColor"
                         fill="none"
                         viewBox="0 0 24 24">

                        <path
                            :class="{'hidden': open, 'inline-flex': !open }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />

                        <path
                            :class="{'hidden': !open, 'inline-flex': open }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />

                    </svg>

                </button>

            </div>

        </div>

    </div>

    {{-- Mobile Menu --}}
    <div x-show="open"
         class="md:hidden border-t border-gray-100 bg-white">

        <div class="px-4 py-4 space-y-2">

            <a href="/"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 font-semibold">
                Home
            </a>

            <a href="/report"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 font-semibold">
                Submit Report
            </a>

            @auth

                <a href="{{ route('messages.index') }}"
                   class="block px-4 py-3 rounded-xl hover:bg-gray-100 font-semibold">
                    Messages
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-3 rounded-xl hover:bg-gray-100 font-semibold">
                    User Settings
                </a>

                @if(auth()->user()->role === 'admin')

                    <a href="/admin/reports"
                       class="block px-4 py-3 rounded-xl hover:bg-gray-100 font-semibold">
                        Admin Dashboard
                    </a>

                    <a href="/admin/teams"
                       class="block px-4 py-3 rounded-xl hover:bg-gray-100 font-semibold">
                        Teams
                    </a>

                @endif

                @if(auth()->user()->role === 'personnel')

                    <a href="/personnel"
                       class="block px-4 py-3 rounded-xl hover:bg-gray-100 font-semibold">
                        Personnel Dashboard
                    </a>

                @endif

                <form method="POST"
                      action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="w-full text-left px-4 py-3 rounded-xl hover:bg-red-50 text-red-600 font-semibold"
                    >
                        Logout
                    </button>

                </form>

            @else

                <a href="/login"
                   class="block px-4 py-3 rounded-xl hover:bg-gray-100 font-semibold">
                    Login
                </a>

                <a href="/register"
                   class="block px-4 py-3 rounded-xl bg-green-700 text-white font-semibold text-center">
                    Register
                </a>

            @endauth

        </div>

    </div>

</nav>