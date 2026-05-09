<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Conversation
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="bg-white rounded-t-2xl shadow-sm p-6 border-b border-gray-100">

                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="text-3xl font-bold text-gray-900">

                            {{ $conversation->name ?: 'Conversation' }}

                        </h1>

                        <p class="text-gray-500 mt-1">
                            {{ $conversation->users->pluck('name')->join(', ') }}
                        </p>

                    </div>

                    <a
                        href="/messages"
                        class="px-5 py-2 bg-gray-600 text-white rounded-xl font-semibold hover:bg-gray-700 transition"
                    >
                        Back
                    </a>

                </div>

            </div>

            {{-- Messages --}}
            <div class="bg-white shadow-sm p-6 space-y-6 max-h-[600px] overflow-y-auto">

                @forelse($conversation->messages as $message)

                    <div class="flex gap-4">

                        {{-- Avatar --}}
                        @if($message->user->profile_photo)

                            <img
                                src="{{ asset('storage/' . $message->user->profile_photo) }}"
                                class="w-12 h-12 rounded-full object-cover border"
                            >

                        @else

                            <div class="w-12 h-12 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">

                                {{ strtoupper(substr($message->user->name ?? 'U',0,1)) }}

                            </div>

                        @endif

                        {{-- Message --}}
                        <div class="flex-1">

                            <div class="bg-gray-100 rounded-2xl p-4">

                                <div class="flex items-center justify-between gap-3">

                                    <h3 class="font-bold text-gray-900">
                                        {{ $message->user->name ?? 'Unknown User' }}
                                    </h3>

                                    <span class="text-xs text-gray-500">
                                        {{ $message->created_at->diffForHumans() }}
                                    </span>

                                </div>

                                <p class="mt-3 text-gray-700 whitespace-pre-line">
                                    {{ $message->body }}
                                </p>

                                @if($message->image)

                                    <img
                                        src="{{ asset('storage/' . $message->image) }}"
                                        class="mt-4 rounded-2xl w-72 max-w-full object-cover shadow-sm border"
                                    >

                                @endif

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-10">

                        <h2 class="text-2xl font-bold text-gray-800">
                            No Messages Yet
                        </h2>

                        <p class="text-gray-500 mt-2">
                            Start the conversation below.
                        </p>

                    </div>

                @endforelse

            </div>

            {{-- Send Message --}}
            <div class="bg-white rounded-b-2xl shadow-sm p-6 border-t border-gray-100">

                <form
                    method="POST"
                    action="/messages/{{ $conversation->id }}"
                    enctype="multipart/form-data"
                    class="space-y-5"
                >

                    @csrf

                    {{-- Textarea --}}
                    <div>

                        <textarea
                            name="body"
                            rows="4"
                            placeholder="Type message..."
                            required
                            class="w-full rounded-2xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                        ></textarea>

                    </div>

                    {{-- Upload --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Upload Image (Optional)
                        </label>

                        <input
                            type="file"
                            name="image"
                            class="block w-full text-sm text-gray-700"
                        >

                    </div>

                    {{-- Button --}}
                    <div>

                        <button
                            type="submit"
                            class="px-6 py-3 bg-green-700 text-white rounded-xl font-semibold hover:bg-green-800 transition"
                        >
                            Send Message
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</x-app-layout>