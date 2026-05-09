<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Team
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm p-8">

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Create Clean-Up Team
                    </h1>

                    <p class="text-gray-600 mt-2">
                        Add a new clean-up personnel team and assign their area.
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/admin/teams" method="POST" class="space-y-6">

                    @csrf

                    {{-- Team Name --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Team Name
                        </label>

                        <input
                            type="text"
                            name="team_name"
                            required
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                            placeholder="Example: Team Alpha"
                        >

                    </div>

                    {{-- Contact Number --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Contact Number
                        </label>

                        <input
                            type="text"
                            name="contact_number"
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                            placeholder="09XXXXXXXXX"
                        >

                    </div>

                    {{-- Assigned Area --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Assigned Area
                        </label>

                        <input
                            type="text"
                            name="assigned_area"
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                            placeholder="Barangay Carmen"
                        >

                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-wrap gap-3 pt-4">

                        <button
                            type="submit"
                            class="px-6 py-3 bg-green-700 text-white rounded-xl font-semibold hover:bg-green-800 transition"
                        >
                            Create Team
                        </button>

                        <a
                            href="/admin/teams"
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