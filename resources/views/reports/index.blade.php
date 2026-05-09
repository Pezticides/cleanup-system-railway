<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Community Clean-Up Reports
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            Community Clean-Up Reports
                        </h1>

                        @auth
                            <p class="text-gray-600 mt-2">
                                Logged in as:
                                <strong>{{ auth()->user()->name }}</strong>
                                ({{ auth()->user()->role }})
                            </p>
                        @endauth
                    </div>

                    <a href="/report"
                       class="inline-flex items-center justify-center px-4 py-2 bg-green-700 text-white rounded-lg font-semibold hover:bg-green-800">
                        + Submit a Report
                    </a>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-xl p-6">
                <form method="GET" action="/" class="grid grid-cols-1 md:grid-cols-4 gap-3">

                    <input
                        type="text"
                        name="search"
                        placeholder="Search by location, concern, or name"
                        value="{{ request('search') }}"
                        class="md:col-span-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                    >

                    <select
                        name="status"
                        class="rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                    >
                        <option value="">All Status</option>

                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="Verified" {{ request('status') == 'Verified' ? 'selected' : '' }}>
                            Verified
                        </option>

                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>
                            In Progress
                        </option>

                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>
                            Completed
                        </option>
                    </select>

                    <div class="flex gap-2">
                        <button
                            type="submit"
                            class="flex-1 px-4 py-2 bg-green-700 text-white rounded-lg font-semibold hover:bg-green-800">
                            Search
                        </button>

                        <a href="/"
                           class="flex-1 text-center px-4 py-2 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700">
                            Reset
                        </a>
                    </div>

                </form>
            </div>

            <h2 class="text-2xl font-bold text-gray-900">
                Report List
            </h2>

            <div class="space-y-5">
                @forelse($reports as $report)

                    <div class="bg-white shadow-sm rounded-xl p-6">

                        <div class="flex flex-col md:flex-row gap-6">

                            @if($report->photo)
                                <img
                                    src="{{ asset('storage/' . $report->photo) }}"
                                    alt="Report photo"
                                    class="w-full md:w-56 h-56 object-cover rounded-xl shadow-sm"
                                >
                            @endif

                            <div class="flex-1">

                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">

                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-900">
                                            {{ $report->concern_type }}
                                        </h3>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Submitted {{ $report->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    <span class="inline-flex w-fit px-3 py-1 rounded-full text-sm font-semibold
                                        @if($report->status == 'Pending') bg-gray-200 text-gray-700
                                        @elseif($report->status == 'Verified') bg-blue-100 text-blue-700
                                        @elseif($report->status == 'In Progress') bg-yellow-100 text-yellow-700
                                        @elseif($report->status == 'Completed') bg-green-100 text-green-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ $report->status }}
                                    </span>

                                </div>

                                <div class="mt-4 space-y-2 text-gray-700">

                                    <p>
                                        <strong>Name:</strong>
                                        {{ $report->reporter_name }}
                                    </p>

                                    <p>
                                        <strong>Location:</strong>
                                        {{ $report->location }}
                                    </p>

                                    <p>
                                        <strong>Description:</strong>
                                        {{ $report->description }}
                                    </p>

                                </div>

                                @if($report->hasCoordinates())
                                    <a
                                        target="_blank"
                                        href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}"
                                        class="inline-block mt-3 text-green-700 font-semibold hover:underline">
                                        Open Location in Google Maps
                                    </a>
                                @endif

                                @auth
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach(['👍','❤️','😮','😢','✅'] as $emoji)
                                            <form method="POST" action="{{ route('reports.react', $report) }}">
                                                @csrf

                                                <input type="hidden" name="emoji" value="{{ $emoji }}">

                                                <button
                                                    type="submit"
                                                    class="px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200 text-sm">
                                                    {{ $emoji }}
                                                    {{ $report->reactions->where('emoji',$emoji)->count() }}
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                @endauth

                                <div class="mt-5">
                                    <a
                                        href="{{ route('reports.show', $report) }}"
                                        class="inline-flex px-4 py-2 bg-green-700 text-white rounded-lg font-semibold hover:bg-green-800">
                                        View Details / Comments
                                    </a>
                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="bg-white shadow-sm rounded-xl p-6">
                        <p class="text-gray-500">
                            No reports found.
                        </p>
                    </div>

                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>