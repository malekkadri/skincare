<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Skin by Noor</title>
    <style>
        :root {
            --text: #111827;
            --muted: #6b7280;
            --border: #dbe0ea;
            --primary: #7c3aed;
            --primary-dark: #6d28d9;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: Inter, system-ui, sans-serif;
            color: var(--text);
            background: radial-gradient(circle at 20% 20%, #e9ddff 0%, #f6f7fb 35%, #eef2ff 100%);
            padding: 1rem;
        }

        .shell {
            width: min(960px, 100%);
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            background: rgba(255, 255, 255, .92);
            border: 1px solid #e6e9f2;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 24px 40px rgba(30, 41, 59, .12);
        }

        .intro {
            padding: 2rem;
            background: linear-gradient(145deg, #1f1147 0%, #312e81 45%, #4338ca 100%);
            color: #eef2ff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 2rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .24);
            padding: .35rem .65rem;
            border-radius: 999px;
            font-size: .78rem;
            width: fit-content;
        }

        .intro h1 { margin: 1rem 0 .8rem; font-size: 1.7rem; line-height: 1.25; }
        .intro p { margin: 0; color: #dbeafe; line-height: 1.6; font-size: .96rem; }

        .points { margin: 0; padding-left: 1rem; color: #dbeafe; font-size: .88rem; line-height: 1.8; }

        .card { padding: 2rem; }
        .card h2 { margin: 0 0 .3rem; font-size: 1.35rem; }
        .sub { margin: 0 0 1.4rem; color: var(--muted); font-size: .92rem; }

        label { display: block; margin: .75rem 0 .35rem; font-weight: 600; font-size: .9rem; }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: .72rem .75rem;
            border: 1px solid var(--border);
            border-radius: .62rem;
            outline: none;
            font-size: .95rem;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, .14);
        }

        .remember {
            margin-top: .85rem;
            display: flex;
            align-items: center;
            gap: .55rem;
            font-size: .9rem;
            color: #374151;
        }

        .btn {
            margin-top: 1.1rem;
            width: 100%;
            padding: .76rem .9rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            border: none;
            border-radius: .62rem;
            cursor: pointer;
            font-weight: 600;
            letter-spacing: .01em;
        }

        .error { font-size: .83rem; color: #dc2626; margin-top: .3rem; }

        @media (max-width: 880px) {
            .shell { grid-template-columns: 1fr; }
            .intro { gap: 1rem; }
        }
    </style>
</head>
<body>
<div class="shell">
    <section class="intro">
        <div>
            <span class="badge">Skin by Noor · Admin</span>
            <h1>Welcome back to your operations center.</h1>
            <p>Manage bookings, content, customer touchpoints, and daily performance from one place.</p>
        </div>
        <ul class="points">
            <li>Track appointments, consultations, and revenue trends.</li>
            <li>Control site content, services, and automation settings.</li>
            <li>Keep your admin workflow secure and organized.</li>
        </ul>
    </section>

    <section class="card">
        <h2>Sign in</h2>
        <p class="sub">Use your admin credentials to continue.</p>

        <form action="{{ route('admin.login.store') }}" method="POST">
            @csrf

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
            @error('email')<div class="error">{{ $message }}</div>@enderror

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
            @error('password')<div class="error">{{ $message }}</div>@enderror

            <label class="remember"><input type="checkbox" name="remember"> Keep me signed in</label>

            <button type="submit" class="btn">Login to Dashboard</button>
        </form>
    </section>
</div>
</body>
</html>
