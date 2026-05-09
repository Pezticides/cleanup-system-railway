<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Report Details
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Activity Timeline --}}
            <div class="bg-white shadow-sm rounded-xl p-6">

                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    Activity Timeline
                </h2>

                <div class="space-y-5">

                    @forelse($report->activities as $activity)

                        <div class="flex gap-4">

                            {{-- Timeline Dot --}}
                            <div class="flex flex-col items-center">

                                <div class="w-4 h-4 rounded-full bg-green-700 mt-1"></div>

                                <div class="w-0.5 flex-1 bg-gray-200"></div>

                            </div>

                            {{-- Content --}}
                            <div class="flex-1 bg-gray-50 rounded-2xl p-4 border border-gray-100">

                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">

                                    <div>

                                        <h3 class="font-bold text-gray-900">
                                            {{ $activity->action }}
                                        </h3>

                                        <p class="text-gray-700 mt-1">
                                            {{ $activity->description }}
                                        </p>

                                    </div>

                                    <div class="text-sm text-gray-500">

                                        {{ $activity->created_at->diffForHumans() }}

                                    </div>

                                </div>

                                @if($activity->user)

                                    <div class="mt-3 flex items-center gap-3">

                                        @if($activity->user->profile_photo)

                                            <img
                                                src="{{ asset('storage/' . $activity->user->profile_photo) }}"
                                                class="w-10 h-10 rounded-full object-cover border"
                                            >

                                        @else

                                            <div class="w-10 h-10 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">

                                                {{ strtoupper(substr($activity->user->name,0,1)) }}

                                            </div>

                                        @endif

                                        <div>

                                            <p class="font-semibold text-gray-900">
                                                {{ $activity->user->name }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                {{ ucfirst($activity->user->role) }}
                                            </p>

                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>

                    @empty

                        <p class="text-gray-500">
                            No activity history yet.
                        </p>

                    @endforelse

                </div>

            </div>
            <div class="bg-white shadow-sm rounded-xl p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $report->concern_type }}
                </h1>

                <div class="space-y-2 text-gray-700">
                    <p><strong>Reporter:</strong> {{ $report->reporter_name }}</p>
                    <p><strong>Location:</strong> {{ $report->location }}</p>
                    <p><strong>Description:</strong> {{ $report->description }}</p>

                    <p>
                        <strong>Status:</strong>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($report->status == 'Pending') bg-gray-200 text-gray-700
                            @elseif($report->status == 'Verified') bg-blue-100 text-blue-700
                            @elseif($report->status == 'In Progress') bg-yellow-100 text-yellow-700
                            @elseif($report->status == 'Completed') bg-green-100 text-green-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ $report->status }}
                        </span>
                    </p>

                    <p><strong>Team:</strong> {{ $report->team?->team_name ?? 'Not Assigned' }}</p>
                    <p><strong>Assigned Personnel:</strong> {{ $report->assignedUser?->name ?? 'Not Assigned' }}</p>
                </div>

                @if($report->photo)
                    <img
                        src="{{ asset('storage/'.$report->photo) }}"
                        alt="Report photo"
                        class="mt-6 rounded-xl max-w-md w-full object-cover shadow"
                    >
                @endif

                @if($report->hasCoordinates())
                    <iframe
                        class="w-full h-72 rounded-xl mt-6 border"
                        loading="lazy"
                        src="https://maps.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}&z=16&output=embed">
                    </iframe>

                    <a
                        target="_blank"
                        href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}"
                        class="inline-block mt-3 text-green-700 font-semibold hover:underline">
                        Open in Google Maps
                    </a>
                @endif

                <p class="text-sm text-gray-500 mt-4">
                    Submitted {{ $report->created_at->diffForHumans() }}
                </p>

                @auth
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach(['👍','❤️','😮','😢','✅'] as $emoji)
                            <form method="POST" action="{{ route('reports.react', $report) }}">
                                @csrf
                                <input type="hidden" name="emoji" value="{{ $emoji }}">

                                <button
                                    type="submit"
                                    class="px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200 text-sm">
                                    {{ $emoji }} {{ $report->reactions->where('emoji',$emoji)->count() }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                @else
                    <p class="mt-4">
                        <a href="/login" class="text-green-700 font-semibold hover:underline">
                            Login to react or comment.
                        </a>
                    </p>
                @endauth
            </div>

            <div class="bg-white shadow-sm rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Comments / Before & After Updates
                </h2>

                @auth
                    <form
                        method="POST"
                        action="{{ route('reports.comments.store', $report) }}"
                        enctype="multipart/form-data"
                        class="space-y-4 mb-6">
                        @csrf

                        <textarea
                            name="body"
                            rows="4"
                            placeholder="Write an update, comment, or before/after note..."
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></textarea>

                        <input
                            type="file"
                            name="photo"
                            accept="image/*"
                            class="block w-full text-sm text-gray-600">

                        <button
                            type="submit"
                            class="px-4 py-2 bg-green-700 text-white rounded-lg font-semibold hover:bg-green-800">
                            Post Comment
                        </button>
                    </form>
                @endauth

                <div class="space-y-5">
                    @forelse($report->comments as $comment)
                        <div class="border-t pt-5 flex gap-4">
                            @if($comment->user->profile_photo)
                                <img
                                    src="{{ asset('storage/'.$comment->user->profile_photo) }}"
                                    class="w-12 h-12 rounded-full object-cover border"
                                    alt="Profile">
                            @else
                                <div class="w-12 h-12 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($comment->user->name,0,1)) }}
                                </div>
                            @endif

                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">
                                    {{ $comment->user->name }}
                                    <span class="text-sm text-gray-500 font-normal">
                                        ({{ $comment->user->role }}) • {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </p>

                                @if($comment->body)
                                    <p class="mt-2 text-gray-700">
                                        {{ $comment->body }}
                                    </p>
                                @endif

                                @if($comment->photo)
                                    <img
                                        src="{{ asset('storage/'.$comment->photo) }}"
                                        alt="Comment photo"
                                        class="mt-3 rounded-lg max-w-sm w-full object-cover shadow">
                                @endif

                                @auth
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach(['👍','❤️','😮','😢','✅'] as $emoji)
                                            <form method="POST" action="{{ route('comments.react', $comment) }}">
                                                @csrf
                                                <input type="hidden" name="emoji" value="{{ $emoji }}">

                                                <button
                                                    type="submit"
                                                    class="px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200 text-sm">
                                                    {{ $emoji }} {{ $comment->reactions->where('emoji',$emoji)->count() }}
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                @endauth
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No comments yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>