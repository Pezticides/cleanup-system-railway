<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Team
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm p-8">

                <div class="mb-8">

                    <h1 class="text-3xl font-bold text-gray-900">
                        Edit Team
                    </h1>

                    <p class="text-gray-600 mt-2">
                        Update team details and assign personnel members.
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

                <form
                    action="/admin/teams/{{ $team->id }}"
                    method="POST"
                    class="space-y-6"
                >

                    @csrf
                    @method('PUT')

                    {{-- Team Name --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Team Name
                        </label>

                        <input
                            type="text"
                            name="team_name"
                            value="{{ $team->team_name }}"
                            required
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
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
                            value="{{ $team->contact_number }}"
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
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
                            value="{{ $team->assigned_area }}"
                            class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                        >

                    </div>

                    {{-- Personnel --}}
                    <div>

                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Assign Personnel
                        </label>

                        <select
                            name="personnel_ids[]"
                            multiple
                            class="w-full rounded-2xl border-gray-300 focus:border-green-500 focus:ring-green-500 h-64"
                        >

                            @foreach($personnels as $personnel)

                                <option
                                    value="{{ $personnel->id }}"
                                    {{ $personnel->clean_up_team_id == $team->id ? 'selected' : '' }}
                                >

                                    {{ $personnel->name }}

                                </option>

                            @endforeach

                        </select>

                        <p class="text-sm text-gray-500 mt-2">
                            Hold CTRL to select multiple personnel.
                        </p>

                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-wrap gap-3 pt-4">

                        <button
                            type="submit"
                            class="px-6 py-3 bg-blue-700 text-white rounded-xl font-semibold hover:bg-blue-800 transition"
                        >
                            Update Team
                        </button>

                        <a
                            href="/admin/teams"
                            class="px-6 py-3 bg-gray-600 text-white rounded-xl font-semibold hover:bg-gray-700 transition"
                        >
                            Back to Teams
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>