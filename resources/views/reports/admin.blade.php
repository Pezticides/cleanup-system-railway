<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f7f4;margin:0}.navbar{background:#1b5e20;color:white;padding:18px 40px;display:flex;justify-content:space-between;align-items:center}.nav-links{display:flex;align-items:center;gap:15px}.navbar a{color:white;text-decoration:none;font-weight:bold}.logout-btn{background:#c62828;border:0;padding:8px 12px;border-radius:6px;color:white;cursor:pointer;font-weight:bold}.container{max-width:1150px;margin:30px auto;padding:20px}.success{background:#d4edda;color:#155724;padding:12px;border-radius:6px;margin-bottom:15px}.card{background:white;padding:20px;margin-bottom:18px;border-radius:10px;box-shadow:0 3px 10px rgba(0,0,0,.1)}.card h3{color:#1b5e20}select{padding:8px;border-radius:6px;border:1px solid #ccc;min-width:190px}button{padding:9px 12px;border:0;border-radius:6px;cursor:pointer;color:white;font-weight:bold;margin-top:8px}.update{background:#1565c0}.delete{background:#c62828}img{margin-top:10px;border-radius:8px;max-width:250px}.status{padding:5px 10px;border-radius:15px;color:white;font-size:12px}.forms{margin-top:15px}.grid{display:grid;grid-template-columns:repeat(5,1fr);gap:15px;margin-bottom:20px}.mini{font-size:13px;color:#555}.row{display:flex;gap:10px;flex-wrap:wrap;align-items:center}.map{width:100%;height:230px;border:0;border-radius:10px;margin-top:12px}@media(max-width:800px){.grid{grid-template-columns:1fr 1fr}.navbar{display:block}.nav-links{margin-top:10px;flex-wrap:wrap}}
    </style>
</head>
<body>
<div class="navbar">
    <h2>Admin Dashboard</h2>
    <div class="nav-links">
        <a href="/">Home</a>
        <a href="/report">Submit Report</a>
        <a href="/admin/reports">Admin</a>
        <a href="{{ route('messages.index') }}">Messages</a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">@csrf<button type="submit" class="logout-btn">Logout</button></form>
    </div>
</div>
<div class="container">
    <h1>Submitted Clean-Up Reports</h1>
    <div class="grid">
        <div class="card"><h3>Total</h3><p>{{ $total }}</p></div>
        <div class="card"><h3>Pending</h3><p>{{ $pending }}</p></div>
        <div class="card"><h3>Verified</h3><p>{{ $verified }}</p></div>
        <div class="card"><h3>In Progress</h3><p>{{ $progress }}</p></div>
        <div class="card"><h3>Completed</h3><p>{{ $completed }}</p></div>
    </div>
    @if(session('success'))<div class="success">{{ session('success') }}</div>@endif

    @forelse($reports as $report)
        <div class="card">
            <h3>{{ $report->concern_type }}</h3>
            <p><strong>Name:</strong> {{ $report->reporter_name }}</p>
            <p><strong>Location:</strong> {{ $report->location }}</p>
            <p><strong>Description:</strong> {{ $report->description }}</p>
            <span class="status" style="background:@if($report->status == 'Pending') gray @elseif($report->status == 'Verified') blue @elseif($report->status == 'In Progress') orange @elseif($report->status == 'Completed') green @else #555 @endif;">{{ $report->status }}</span>
            <p class="mini">Submitted: {{ $report->created_at->diffForHumans() }}</p>
            @if($report->photo)<img src="{{ asset('storage/' . $report->photo) }}" alt="Report photo">@endif
            @if($report->hasCoordinates())
                <iframe class="map" loading="lazy" src="https://maps.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}&z=16&output=embed"></iframe>
                <p><a target="_blank" href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}">Open exact pin in Google Maps</a></p>
            @endif
            <p><strong>Current Team:</strong> {{ $report->team?->team_name ?? 'Not Assigned' }}</p>
            <p><strong>Assigned Personnel:</strong> {{ $report->assignedUser?->name ?? 'Not Assigned' }}</p>

            <div class="forms">
                <form action="/admin/reports/{{ $report->id }}/status" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <select name="status">
                            @foreach(['Pending','Verified','In Progress','Completed'] as $status)
                                <option value="{{ $status }}" {{ $report->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                        <select name="clean_up_team_id">
                            <option value="">No Team</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ $report->clean_up_team_id == $team->id ? 'selected' : '' }}>{{ $team->team_name }}</option>
                            @endforeach
                        </select>
                        <select name="assigned_user_id">
                            <option value="">Auto assign personnel</option>
                            @foreach($personnels as $personnel)
                                <option value="{{ $personnel->id }}" {{ $report->assigned_user_id == $personnel->id ? 'selected' : '' }}>
                                    {{ $personnel->name }} ({{ $personnel->cleanUpTeam?->team_name ?? 'No team' }})
                                </option>
                            @endforeach
                        </select>
                        <button class="update" type="submit">Update Assignment</button>
                    </div>
                    <p class="mini">Tip: leave personnel as “Auto assign” to assign the least busy personnel.</p>
                </form>
                <form action="/admin/reports/{{ $report->id }}" method="POST" style="margin-top:8px;">@csrf @method('DELETE')<button class="delete" type="submit" onclick="return confirm('Delete this report?')">Delete</button></form>
            </div>
            <p><a href="{{ route('reports.show', $report) }}">View comments / reactions</a></p>
        </div>
    @empty
        <div class="card"><p>No reports found.</p></div>
    @endforelse
</div>
</body>
</html>
