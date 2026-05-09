<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Personnel Dashboard
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Header Card --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>
                        <h1 class="text-4xl font-bold text-gray-900">
                            My Assigned Reports
                        </h1>

                        <p class="text-gray-600 mt-2">
                            View and manage your assigned clean-up tasks.
                        </p>
                    </div>

                </div>
            </div>

            {{-- Reports --}}
            <div class="space-y-6">

                @forelse($reports as $report)

                    <div class="bg-white rounded-2xl shadow-sm p-6">

                        <div class="flex flex-col lg:flex-row gap-6">

                            {{-- Photo --}}
                            @if($report->photo)

                                <div class="lg:w-72">
                                    <img
                                        src="{{ asset('storage/' . $report->photo) }}"
                                        alt="Report photo"
                                        class="w-full h-72 object-cover rounded-2xl shadow-sm"
                                    >
                                </div>

                            @endif

                            {{-- Details --}}
                            <div class="flex-1">

                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                                    <div>

                                        <h2 class="text-3xl font-bold text-gray-900">
                                            {{ $report->concern_type }}
                                        </h2>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Assigned to you
                                        </p>

                                    </div>

                                    {{-- Status Badge --}}
                                    <span class="inline-flex w-fit px-4 py-1 rounded-full text-sm font-semibold
                                        @if($report->status == 'Pending')
                                            bg-gray-200 text-gray-700
                                        @elseif($report->status == 'Verified')
                                            bg-blue-100 text-blue-700
                                        @elseif($report->status == 'In Progress')
                                            bg-yellow-100 text-yellow-700
                                        @elseif($report->status == 'Completed')
                                            bg-green-100 text-green-700
                                        @else
                                            bg-gray-100 text-gray-700
                                        @endif">

                                        {{ $report->status }}

                                    </span>

                                </div>

                                {{-- Report Info --}}
                                <div class="mt-5 space-y-3 text-gray-700">

                                    <p>
                                        <strong>Location:</strong>
                                        {{ $report->location }}
                                    </p>

                                    <p>
                                        <strong>Description:</strong>
                                        {{ $report->description }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        Use comments to upload before and after clean-up updates.
                                    </p>

                                </div>

                                {{-- Google Maps --}}
                                @if($report->hasCoordinates())

                                    <div class="mt-5">

                                        <a
                                            target="_blank"
                                            href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}"
                                            class="inline-block text-green-700 font-semibold hover:underline"
                                        >
                                            Open pinned location in Google Maps
                                        </a>

                                    </div>

                                @endif

                                {{-- Buttons --}}
                                <div class="mt-6 flex flex-wrap gap-3">

                                    <a
                                        href="{{ route('reports.show', $report) }}"
                                        class="px-5 py-2 bg-green-700 text-white rounded-xl font-semibold hover:bg-green-800 transition"
                                    >
                                        Open Report / Add Update
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="bg-white rounded-2xl shadow-sm p-8 text-center">

                        <h2 class="text-2xl font-bold text-gray-800">
                            No Assigned Reports Yet
                        </h2>

                        <p class="text-gray-500 mt-2">
                            Reports assigned to you will appear here.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>