<!DOCTYPE html>
<html>
<head>
    <title>Personnel Dashboard</title>
    <style>body{font-family:Arial;background:#f4f7f4;margin:0}.navbar{background:#1b5e20;color:white;padding:18px 40px;display:flex;justify-content:space-between}.navbar a{color:white;text-decoration:none;margin-left:15px;font-weight:bold}.container{max-width:950px;margin:30px auto}.card{background:white;padding:18px;border-radius:10px;box-shadow:0 3px 10px rgba(0,0,0,.1);margin-bottom:15px}.btn{background:#2e7d32;color:white;padding:9px 12px;border-radius:6px;text-decoration:none;display:inline-block;border:0;cursor:pointer}.logout{background:#c62828}.photo{max-width:250px;border-radius:8px}.muted{color:#666;font-size:13px}</style>
</head>
<body>
<div class="navbar"><h2>Personnel Dashboard</h2><div><a href="/">Home</a><a href="{{ route('messages.index') }}">Messages</a><form method="POST" action="{{ route('logout') }}" style="display:inline">@csrf<button class="btn logout" type="submit">Logout</button></form></div></div>
<div class="container">
    <h1>My Assigned Reports</h1>
    @forelse($reports as $report)
        <div class="card">
            <h3>{{ $report->concern_type }}</h3>
            <p><strong>Location:</strong> {{ $report->location }}</p>
            <p><strong>Description:</strong> {{ $report->description }}</p>
            <p><strong>Status:</strong> {{ $report->status }}</p>
            @if($report->photo)<img class="photo" src="{{ asset('storage/' . $report->photo) }}">@endif
            @if($report->hasCoordinates())<p><a target="_blank" href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}">Open pinned location</a></p>@endif
            <p class="muted">Assigned to you. Use comments for before/after photos.</p>
            <a class="btn" href="{{ route('reports.show', $report) }}">Open / Add Update</a>
        </div>
    @empty
        <div class="card">No assigned reports yet.</div>
    @endforelse
</div>
</body>
</html>
