<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Taya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream: #FFF0E5;
            --black: #111111;
            --border: 2px solid #111111;
            --radius: 4px;
        }
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Space Mono', monospace;
            background: var(--cream);
            color: var(--black);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrap {
            width: 100%;
            max-width: 380px;
            padding: 20px;
        }

        .login-badge {
            display: inline-block;
            background: var(--black);
            color: var(--cream);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.15em;
            padding: 4px 10px;
            border-radius: var(--radius);
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .login-title {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .login-sub {
            font-size: 11px;
            color: #666;
            margin-bottom: 32px;
        }

        .login-card {
            border: var(--border);
            border-radius: var(--radius);
            padding: 28px;
            background: #fff8f2;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 16px;
        }

        .form-group label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #666;
        }

        .form-control {
            background: transparent;
            border: var(--border);
            border-radius: var(--radius);
            padding: 10px 12px;
            font-family: 'Space Mono', monospace;
            font-size: 13px;
            color: var(--black);
            outline: none;
            transition: border-color 0.15s;
        }
        .form-control:focus { border-color: #555; }
        .form-control::placeholder { color: #bbb; }

        .error-msg {
            background: #f7d4d4;
            border: 1px solid #c0392b;
            color: #7a1a1a;
            font-size: 11px;
            padding: 9px 12px;
            border-radius: var(--radius);
            margin-bottom: 16px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: var(--black);
            color: var(--cream);
            border: var(--border);
            border-radius: var(--radius);
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.05em;
            transition: background 0.15s, transform 0.1s;
            margin-top: 4px;
        }
        .btn-login:hover { background: #333; }
        .btn-login:active { transform: translate(2px, 2px); }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 11px;
            color: #888;
            text-decoration: none;
        }
        .back-link:hover { color: var(--black); }
    </style>
</head>
<body>
<div class="login-wrap">
    <span class="login-badge">Admin Area</span>
    <h1 class="login-title">Selamat datang,<br>Taya.</h1>
    <p class="login-sub">Masuk untuk melihat dashboard portofolio.</p>

    <div class="login-card">
        @if ($errors->any())
            <div class="error-msg">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="email@kamu.com"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="••••••••"
                    required
                >
            </div>
            <button type="submit" class="btn-login">Masuk →</button>
        </form>
    </div>

    <a href="/" class="back-link">← Kembali ke portofolio</a>
</div>
</body>
</html>
