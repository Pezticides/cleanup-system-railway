<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            New Conversation
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm p-8">

                {{-- Header --}}
                <div class="mb-8">

                    <h1 class="text-3xl font-bold text-gray-900">
                        Create Message / Group
                    </h1>

                    <p class="text-gray-600 mt-2">
                        Start a conversation with personnel, admins, or users.
                    </p>

                </div>

                {{-- Errors --}}
                @if($errors->any())

                    <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl">

                        <ul class="list-disc list-inside">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                {{-- Form --}}
                <form
                    method="POST"
                    action="{{ route('messages.store') }}"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >

                    @csrf

                    {{-- Group Name --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Group Name (Optional)
                        </label>

                        <input
                            type="text"
                            name="name"
                            placeholder="Example: Team Alpha Cleanup Group"
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >

                    </div>

                    {{-- Users --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Select Members
                        </label>

                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 max-h-80 overflow-y-auto space-y-3">

                            @foreach($users as $user)

                                <label class="flex items-center gap-4 p-3 bg-white rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer">

                                    {{-- Avatar --}}
                                    @if($user->profile_photo)

                                        <img
                                            src="{{ asset('storage/' . $user->profile_photo) }}"
                                            class="w-12 h-12 rounded-full object-cover border"
                                        >

                                    @else

                                        <div class="w-12 h-12 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">
                                            {{ strtoupper(substr($user->name,0,1)) }}
                                        </div>

                                    @endif

                                    {{-- Checkbox --}}
                                    <input
                                        type="checkbox"
                                        name="user_ids[]"
                                        value="{{ $user->id }}"
                                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                    >

                                    {{-- User Info --}}
                                    <div class="flex-1">

                                        <p class="font-semibold text-gray-900">
                                            {{ $user->name }}
                                        </p>

                                        <p class="text-sm text-gray-500">
                                            {{ $user->role }} • {{ $user->email }}
                                        </p>

                                    </div>

                                </label>

                            @endforeach

                        </div>

                    </div>

                    {{-- Message --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            First Message
                        </label>

                        <textarea
                            name="body"
                            rows="5"
                            placeholder="Write a message..."
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                        ></textarea>

                    </div>

                    {{-- Upload --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Upload Image (Optional)
                        </label>

                        <input
                            type="file"
                            name="photo"
                            accept="image/*"
                            class="block w-full text-sm text-gray-700"
                        >

                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-wrap gap-3 pt-4">

                        <button
                            type="submit"
                            class="px-6 py-3 bg-green-700 text-white rounded-xl font-semibold hover:bg-green-800 transition"
                        >
                            Create Conversation
                        </button>

                        <a
                            href="{{ route('messages.index') }}"
                            class="px-6 py-3 bg-gray-600 text-white rounded-xl font-semibold hover:bg-gray-700 transition"
                        >
                            Back
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>
</x-app-layout>