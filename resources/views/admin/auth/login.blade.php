<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Skin by Noor</title>
    <style>
        body{margin:0;display:grid;place-items:center;min-height:100vh;background:#f3f4f6;font-family:Inter,system-ui,sans-serif}
        .card{width:min(420px,92vw);background:#fff;padding:1.5rem;border:1px solid #e5e7eb;border-radius:.75rem}
        label{display:block;margin:.8rem 0 .35rem;font-weight:600} input{width:100%;padding:.65rem;border:1px solid #d1d5db;border-radius:.5rem}
        .btn{margin-top:1rem;width:100%;padding:.7rem;background:#7c3aed;color:#fff;border:none;border-radius:.5rem;cursor:pointer}
        .error{font-size:.85rem;color:#dc2626;margin-top:.3rem}
    </style>
</head>
<body>
<div class="card">
    <h1>Admin Login</h1>
    <p>Sign in to manage Skin by Noor settings.</p>

    <form action="{{ route('admin.login.store') }}" method="POST">
        @csrf
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')<div class="error">{{ $message }}</div>@enderror

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
        @error('password')<div class="error">{{ $message }}</div>@enderror

        <label><input type="checkbox" name="remember"> Remember me</label>

        <button type="submit" class="btn">Login</button>
    </form>
</div>
</body>
</html>
