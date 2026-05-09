<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
                <div class="bg-white rounded-2xl shadow-sm p-6"><p class="text-gray-500 text-sm">Total Reports</p><h2 class="text-3xl font-bold text-gray-900 mt-2">{{ $total }}</h2></div>
                <div class="bg-white rounded-2xl shadow-sm p-6"><p class="text-gray-500 text-sm">Pending</p><h2 class="text-3xl font-bold text-gray-700 mt-2">{{ $pending }}</h2></div>
                <div class="bg-white rounded-2xl shadow-sm p-6"><p class="text-gray-500 text-sm">Verified</p><h2 class="text-3xl font-bold text-blue-700 mt-2">{{ $verified }}</h2></div>
                <div class="bg-white rounded-2xl shadow-sm p-6"><p class="text-gray-500 text-sm">In Progress</p><h2 class="text-3xl font-bold text-yellow-600 mt-2">{{ $progress }}</h2></div>
                <div class="bg-white rounded-2xl shadow-sm p-6"><p class="text-gray-500 text-sm">Completed</p><h2 class="text-3xl font-bold text-green-700 mt-2">{{ $completed }}</h2></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-5">Reports by Status</h2>
                    <canvas id="statusChart"></canvas>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-5">Reports by Concern Type</h2>
                    <canvas id="concernChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-5">Monthly Reports Analytics</h2>
                <canvas id="monthlyChart"></canvas>
            </div>

            <div class="space-y-6">
                @forelse($reports as $report)
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex flex-col lg:flex-row gap-6">
                            @if($report->photo)
                                <div class="lg:w-72">
                                    <img src="{{ asset('storage/' . $report->photo) }}" alt="Report photo" class="w-full h-72 object-cover rounded-2xl shadow-sm">
                                </div>
                            @endif

                            <div class="flex-1">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div>
                                        <h2 class="text-3xl font-bold text-gray-900">{{ $report->concern_type }}</h2>
                                        <p class="text-sm text-gray-500 mt-1">Submitted {{ $report->created_at->diffForHumans() }}</p>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex w-fit px-4 py-1 rounded-full text-sm font-semibold
                                            @if($report->status == 'Pending') bg-gray-200 text-gray-700
                                            @elseif($report->status == 'Verified') bg-blue-100 text-blue-700
                                            @elseif($report->status == 'In Progress') bg-yellow-100 text-yellow-700
                                            @elseif($report->status == 'Completed') bg-green-100 text-green-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            {{ $report->status }}
                                        </span>

                                        <span class="inline-flex w-fit px-4 py-1 rounded-full text-sm font-semibold
                                            @if($report->priority == 'Low') bg-gray-200 text-gray-700
                                            @elseif($report->priority == 'Medium') bg-blue-100 text-blue-700
                                            @elseif($report->priority == 'High') bg-orange-100 text-orange-700
                                            @elseif($report->priority == 'Urgent') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            {{ $report->priority ?? 'Medium' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-5 space-y-3 text-gray-700">
                                    <p><strong>Reporter:</strong> {{ $report->reporter_name }}</p>
                                    <p><strong>Location:</strong> {{ $report->location }}</p>
                                    <p><strong>Description:</strong> {{ $report->description }}</p>
                                    <p><strong>Priority:</strong> {{ $report->priority ?? 'Medium' }}</p>
                                    <p><strong>Current Team:</strong> {{ $report->team?->team_name ?? 'Not Assigned' }}</p>
                                    <p><strong>Assigned Personnel:</strong> {{ $report->assignedUser?->name ?? 'Not Assigned' }}</p>
                                </div>

                                @if($report->hasCoordinates())
                                    <div class="mt-5">
                                        <iframe class="w-full h-64 rounded-2xl border border-gray-200" loading="lazy" src="https://maps.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}&z=16&output=embed"></iframe>
                                        <a target="_blank" href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" class="inline-block mt-3 text-green-700 font-semibold hover:underline">
                                            Open exact pin in Google Maps
                                        </a>
                                    </div>
                                @endif

                                <div class="mt-8 bg-gray-50 rounded-2xl p-5 border border-gray-200">
                                    <h3 class="text-lg font-bold text-gray-900 mb-4">Update Assignment</h3>

                                    <form action="/admin/reports/{{ $report->id }}/status" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PATCH')

                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                                <select name="status" class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500">
                                                    @foreach(['Pending','Verified','In Progress','Completed'] as $status)
                                                        <option value="{{ $status }}" {{ $report->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Priority</label>
                                                <select name="priority" class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500">
                                                    @foreach(['Low','Medium','High','Urgent'] as $priority)
                                                        <option value="{{ $priority }}" {{ ($report->priority ?? 'Medium') == $priority ? 'selected' : '' }}>{{ $priority }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Team</label>
                                                <select name="clean_up_team_id" class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500">
                                                    <option value="">No Team</option>
                                                    @foreach($teams as $team)
                                                        <option value="{{ $team->id }}" {{ $report->clean_up_team_id == $team->id ? 'selected' : '' }}>{{ $team->team_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Personnel</label>
                                                <select name="assigned_user_id" class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500">
                                                    <option value="">Auto assign personnel</option>
                                                    @foreach($personnels as $personnel)
                                                        <option value="{{ $personnel->id }}" {{ $report->assigned_user_id == $personnel->id ? 'selected' : '' }}>
                                                            {{ $personnel->name }} ({{ $personnel->cleanUpTeam?->team_name ?? 'No team' }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <p class="text-sm text-gray-500">Leave personnel as auto assign to assign the least busy personnel.</p>

                                        <div class="flex flex-wrap gap-3 pt-2">
                                            <button type="submit" class="px-5 py-2 bg-blue-700 text-white rounded-xl font-semibold hover:bg-blue-800">
                                                Update Assignment
                                            </button>

                                            <a href="{{ route('reports.show', $report) }}" class="px-5 py-2 bg-green-700 text-white rounded-xl font-semibold hover:bg-green-800">
                                                View Comments / Reactions
                                            </a>
                                        </div>
                                    </form>
                                </div>

                                <form action="/admin/reports/{{ $report->id }}" method="POST" class="mt-4">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" onclick="return confirm('Delete this report?')" class="px-5 py-2 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700">
                                        Delete Report
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <p class="text-gray-500">No reports found.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Verified', 'In Progress', 'Completed'],
                datasets: [{
                    data: [{{ $pending }}, {{ $verified }}, {{ $progress }}, {{ $completed }}],
                    backgroundColor: ['#9ca3af', '#3b82f6', '#f59e0b', '#16a34a']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        new Chart(document.getElementById('concernChart'), {
            type: 'bar',
            data: {
                labels: @json($concernLabels),
                datasets: [{
                    label: 'Reports',
                    data: @json($concernData),
                    backgroundColor: '#15803d',
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });

        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: @json($monthlyLabels),
                datasets: [{
                    label: 'Monthly Reports',
                    data: @json($monthlyData),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.15)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
</x-app-layout>