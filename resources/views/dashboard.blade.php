@php
    $role = auth()->user()->role;
@endphp

@if($role === 'admin')
    <script>
        window.location.href = "/admin/reports";
    </script>
@elseif($role === 'personnel')
    <script>
        window.location.href = "/personnel";
    </script>
@else
    <script>
        window.location.href = "/";
    </script>
@endif