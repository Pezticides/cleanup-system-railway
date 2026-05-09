<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Teams Management
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            Clean-Up Teams
                        </h1>

                        <p class="text-gray-600 mt-2">
                            Manage clean-up teams, contact numbers, assigned areas, and reports.
                        </p>
                    </div>

                    <a href="/admin/teams/create"
                       class="px-5 py-2 bg-green-700 text-white rounded-xl font-semibold hover:bg-green-800">
                        + Create Team
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900">
                        All Teams
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Team Name</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Contact</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Assigned Area</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Total Reports</th>
                                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($teams as $team)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-semibold text-gray-900">
                                        {{ $team->team_name }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $team->contact_number ?? 'No contact' }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-700">
                                        {{ $team->assigned_area ?? 'No area assigned' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                            {{ $team->reports_count }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="/admin/teams/{{ $team->id }}/edit"
                                               class="px-4 py-2 bg-blue-700 text-white rounded-lg text-sm font-semibold hover:bg-blue-800">
                                                Edit
                                            </a>

                                            <form action="/admin/teams/{{ $team->id }}" method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        onclick="return confirm('Delete this team?')"
                                                        class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No teams found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>