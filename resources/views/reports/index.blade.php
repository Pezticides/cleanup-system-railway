<!DOCTYPE html>
<html>
<head>
    <title>Community Clean-Up Reports</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f7f4;margin:0}.navbar{background:#1b5e20;color:white;padding:18px 40px;display:flex;justify-content:space-between;align-items:center}.navbar a,.logout-btn{color:white;text-decoration:none;margin-left:15px;font-weight:bold;background:none;border:none;cursor:pointer}.container{max-width:950px;margin:30px auto;padding:20px}.btn{display:inline-block;padding:10px 14px;background:#2e7d32;color:white;text-decoration:none;border-radius:6px;border:0;cursor:pointer;font-weight:bold}.search-box,.card{background:white;padding:18px;border-radius:8px;margin-bottom:15px;box-shadow:0 2px 6px rgba(0,0,0,.08)}input,select{padding:10px;border-radius:6px;border:1px solid #ccc}.status{font-weight:bold;color:blue}img{margin-top:10px;border-radius:8px;max-width:220px}.success{color:green;font-weight:bold}.reaction-btn{border:0;border-radius:20px;padding:5px 8px;cursor:pointer;margin:3px}.muted{color:#666;font-size:13px}
    </style>
</head>
<body>
<div class="navbar">
    <h2>Clean-Up Reporting System</h2>
    <div>
        <a href="/">Home</a><a href="/report">Submit Report</a>@auth <a href="{{ route('messages.index') }}">Messages</a> @endauth<a href="/login">Admin</a>
        @auth<form method="POST" action="{{ route('logout') }}" style="display:inline;">@csrf<button type="submit" class="logout-btn">Logout</button></form>@else<a href="/login">Login</a><a href="/register">Register</a>@endauth
    </div>
</div>
<div class="container">
    <h1>Community Clean-Up Reports</h1>
    @if(session('success'))<p class="success">{{ session('success') }}</p>@endif
    @auth<p>Logged in as: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role }})</p>@endauth
    <a href="/report" class="btn">+ Submit a Report</a>
    <div class="search-box"><form method="GET" action="/"><input type="text" name="search" placeholder="Search by location, concern, or name" value="{{ request('search') }}" style="width:300px;"><select name="status"><option value="">All Status</option><option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option><option value="Verified" {{ request('status') == 'Verified' ? 'selected' : '' }}>Verified</option><option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option><option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option></select><button type="submit" class="btn">Search</button><a href="/" class="btn" style="background:#777;">Reset</a></form></div>
    <h2>Report List</h2>
    @forelse($reports as $report)
        <div class="card">
            <h3>{{ $report->concern_type }}</h3>
            <p><strong>Name:</strong> {{ $report->reporter_name }}</p>
            <p><strong>Location:</strong> {{ $report->location }}</p>
            <p><strong>Description:</strong> {{ $report->description }}</p>
            <p class="status">Status: {{ $report->status }}</p>
            <p class="muted">Submitted: {{ $report->created_at->diffForHumans() }}</p>
            @if($report->photo)<img src="{{ asset('storage/' . $report->photo) }}" alt="Report photo">@endif
            @if($report->hasCoordinates())<p><a target="_blank" href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}">Open Location in Google Maps</a></p>@endif
            @auth<div style="margin-top:10px;">@foreach(['👍','❤️','😮','😢','✅'] as $emoji)<form method="POST" action="{{ route('reports.react', $report) }}" style="display:inline;">@csrf<input type="hidden" name="emoji" value="{{ $emoji }}"><button type="submit" class="reaction-btn">{{ $emoji }} {{ $report->reactions->where('emoji',$emoji)->count() }}</button></form>@endforeach</div>@endauth
            <p><a href="{{ route('reports.show', $report) }}" class="btn">View Details / Comments</a></p>
        </div>
    @empty<div class="card"><p>No reports found.</p></div>@endforelse
</div>
</body>
</html>
