<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
</head>
<body>

<h1>Conversation</h1>

<a href="/messages">Back to Inbox</a>

<hr>

@foreach($conversation->messages as $message)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <strong>{{ $message->user->name ?? 'Unknown User' }}</strong>
        <p>{{ $message->body }}</p>

        @if($message->image)
            <img src="{{ asset('storage/' . $message->image) }}" width="200">
        @endif

        <small>{{ $message->created_at->diffForHumans() }}</small>
    </div>
@endforeach

<form method="POST" action="/messages/{{ $conversation->id }}" enctype="multipart/form-data">
    @csrf

    <textarea name="body" placeholder="Type message..." required></textarea><br><br>

    <input type="file" name="image"><br><br>

    <button type="submit">Send</button>
</form>

</body>
</html>