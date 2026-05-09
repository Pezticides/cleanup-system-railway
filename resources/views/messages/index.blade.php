<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Messages
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            Conversations
                        </h1>

                        <p class="text-gray-600 mt-2">
                            View your messages and group conversations.
                        </p>
                    </div>

                    <a href="{{ route('messages.create') }}"
                       class="px-5 py-2 bg-green-700 text-white rounded-xl font-semibold hover:bg-green-800 transition">
                        + New Conversation
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($conversations as $conversation)

                    <a href="{{ route('messages.show',$conversation) }}"
                       class="block bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition">

                        <div class="flex items-center gap-4">

                            <div class="flex -space-x-3">
                                @foreach($conversation->users->take(3) as $user)
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                             class="w-12 h-12 rounded-full object-cover border-2 border-white">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-green-700 text-white flex items-center justify-center font-bold border-2 border-white">
                                            {{ strtoupper(substr($user->name,0,1)) }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900">
                                    {{ $conversation->name ?: $conversation->users->where('id','!=',auth()->id())->pluck('name')->join(', ') }}
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    Members: {{ $conversation->users->pluck('name')->join(', ') }}
                                </p>
                            </div>

                        </div>

                    </a>

                @empty

                    <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                        <h2 class="text-2xl font-bold text-gray-800">
                            No Conversations Yet
                        </h2>

                        <p class="text-gray-500 mt-2">
                            Start a new message or create a group conversation.
                        </p>
                    </div>

                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>