<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial; background:#f4f7f4; margin:0; }
        .navbar {
            background:#1b5e20; color:white; padding:18px 40px;
            display:flex; justify-content:space-between; align-items:center;
        }
        .navbar a { color:white; text-decoration:none; margin-left:15px; font-weight:bold; }
        .page {
            display:flex; justify-content:center; align-items:center;
            min-height:calc(100vh - 80px);
        }
        .card {
            background:white; padding:30px; width:380px;
            border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,.1);
        }
        h2 { text-align:center; color:#1b5e20; }
        input, select {
            width:100%; padding:10px; margin:8px 0;
            border-radius:6px; border:1px solid #ccc;
        }
        button {
            width:100%; padding:10px; background:#1b5e20;
            color:white; border:none; border-radius:6px; cursor:pointer;
        }
        .link { display:block; text-align:center; margin-top:10px; color:#1b5e20; }
        .error { color:red; font-size:13px; }
        .privacy { font-size:13px; margin:10px 0; }
        .privacy input { width:auto; }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Clean-Up Reporting System</h2>
    <div>
        <a href="/">Home</a>
        <a href="/report">Submit Report</a>
        <a href="/login">Login</a>
        <a href="/register">Register</a>
    </div>
</div>

<div class="page">
    <div class="card">
        <h2>Create Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input type="text" name="name" placeholder="Full Name" required>
            @error('name') <div class="error">{{ $message }}</div> @enderror

            <input type="email" name="email" placeholder="Email" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <select name="role" required>
                <option value="citizen">Citizen</option>
                <option value="personnel">Clean-Up Personnel</option>
            </select>
            @error('role') <div class="error">{{ $message }}</div> @enderror

            <input type="password" name="password" placeholder="Password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

            <label class="privacy">
                <input type="checkbox" name="privacy" required>
                I agree to the privacy policy.
            </label>
            @error('privacy') <div class="error">{{ $message }}</div> @enderror

            <button type="submit">Register</button>
        </form>

        <a href="/login" class="link">Already have an account?</a>
    </div>
</div>

</body>
</html>