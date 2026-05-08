<!DOCTYPE html>
<html>
<head>
    <title>Report Details</title>
    <style>
        body{font-family:Arial,sans-serif;background:#f4f7f4;margin:0}.navbar{background:#1b5e20;color:white;padding:18px 40px;display:flex;justify-content:space-between;align-items:center}.navbar a{color:white;text-decoration:none;margin-left:15px;font-weight:bold}.container{max-width:950px;margin:30px auto;padding:20px}.card{background:white;padding:20px;border-radius:10px;box-shadow:0 3px 10px rgba(0,0,0,.1);margin-bottom:18px}.btn{display:inline-block;padding:9px 12px;background:#2e7d32;color:white;text-decoration:none;border:0;border-radius:6px;cursor:pointer;font-weight:bold}textarea,input{width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;margin:8px 0}.photo{max-width:320px;border-radius:8px;margin-top:10px}.comment{border-top:1px solid #ddd;padding-top:12px;margin-top:12px}.reaction-form{display:inline}.reaction-btn{background:#eee;color:#222;border:0;border-radius:20px;padding:6px 9px;margin:3px;cursor:pointer}.map{width:100%;height:300px;border:0;border-radius:10px;margin-top:12px}.muted{color:#666;font-size:13px}.logout-btn{background:#c62828;color:white;border:0;padding:8px 12px;border-radius:6px;cursor:pointer;font-weight:bold}
    </style>
</head>
<body>
<div class="navbar">
    <h2>Report Details</h2>
    <div>
        <a href="/">Home</a><a href="/report">Submit Report</a><a href="{{ route('messages.index') }}">Messages</a>
        @auth
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">@csrf<button class="logout-btn" type="submit">Logout</button></form>
        @else
            <a href="/login">Login</a><a href="/register">Register</a>
        @endauth
    </div>
</div>
<div class="container">
    @if(session('success'))<div class="card" style="color:green">{{ session('success') }}</div>@endif
    <div class="card">
        <h1>{{ $report->concern_type }}</h1>
        <p><strong>Reporter:</strong> {{ $report->reporter_name }}</p>
        <p><strong>Location:</strong> {{ $report->location }}</p>
        <p><strong>Description:</strong> {{ $report->description }}</p>
        <p><strong>Status:</strong> {{ $report->status }}</p>
        <p><strong>Team:</strong> {{ $report->team?->team_name ?? 'Not Assigned' }}</p>
        <p><strong>Assigned Personnel:</strong> {{ $report->assignedUser?->name ?? 'Not Assigned' }}</p>
        @if($report->photo)<img class="photo" src="{{ asset('storage/'.$report->photo) }}" alt="Report photo">@endif
        @if($report->hasCoordinates())
            <iframe class="map" loading="lazy" src="https://maps.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}&z=16&output=embed"></iframe>
            <p><a target="_blank" href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}">Open in Google Maps</a></p>
        @endif
        <p class="muted">Submitted {{ $report->created_at->diffForHumans() }}</p>

        @auth
            <div>
                @foreach(['👍','❤️','😮','😢','✅'] as $emoji)
                    <form class="reaction-form" method="POST" action="{{ route('reports.react', $report) }}">@csrf<input type="hidden" name="emoji" value="{{ $emoji }}"><button class="reaction-btn" type="submit">{{ $emoji }} {{ $report->reactions->where('emoji',$emoji)->count() }}</button></form>
                @endforeach
            </div>
        @else
            <p><a href="/login">Login to react or comment.</a></p>
        @endauth
    </div>

    <div class="card">
        <h2>Comments / Before & After Updates</h2>
        @auth
            <form method="POST" action="{{ route('reports.comments.store', $report) }}" enctype="multipart/form-data">
                @csrf
                <textarea name="body" placeholder="Write an update, comment, or before/after note..."></textarea>
                <input type="file" name="photo" accept="image/*">
                <button class="btn" type="submit">Post Comment</button>
            </form>
        @endauth
        @forelse($report->comments as $comment)
            <div class="comment">
                <p><strong>{{ $comment->user->name }}</strong> <span class="muted">({{ $comment->user->role }}) • {{ $comment->created_at->diffForHumans() }}</span></p>
                @if($comment->body)<p>{{ $comment->body }}</p>@endif
                @if($comment->photo)<img class="photo" src="{{ asset('storage/'.$comment->photo) }}" alt="Comment photo">@endif
                @auth
                    <div>
                        @foreach(['👍','❤️','😮','😢','✅'] as $emoji)
                            <form class="reaction-form" method="POST" action="{{ route('comments.react', $comment) }}">@csrf<input type="hidden" name="emoji" value="{{ $emoji }}"><button class="reaction-btn" type="submit">{{ $emoji }} {{ $comment->reactions->where('emoji',$emoji)->count() }}</button></form>
                        @endforeach
                    </div>
                @endauth
            </div>
        @empty
            <p>No comments yet.</p>
        @endforelse
    </div>
</div>
</body>
</html>
