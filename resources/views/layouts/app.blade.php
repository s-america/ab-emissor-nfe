<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'AB Emissor NF-e'))</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f6f7f9;
            --panel: #ffffff;
            --ink: #17202a;
            --muted: #5b6673;
            --line: #dce2e8;
            --brand: #0f766e;
            --brand-strong: #115e59;
            --warn: #92400e;
            --danger: #b42318;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        a { color: inherit; }
        .topbar {
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            border-bottom: 1px solid var(--line);
            background: var(--panel);
        }

        .brand {
            font-weight: 700;
            font-size: 18px;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav a {
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
        }

        .nav a:hover {
            color: var(--ink);
        }

        .shell {
            width: min(1120px, calc(100vw - 32px));
            margin: 28px auto;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 24px;
        }

        .auth-wrap {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .auth-box {
            width: min(420px, 100%);
        }

        .field {
            display: grid;
            gap: 8px;
            margin-bottom: 16px;
        }

        label {
            font-size: 14px;
            font-weight: 700;
        }

        input,
        select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 11px 12px;
            font-size: 15px;
            background: #fff;
        }

        .check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            color: var(--muted);
            font-size: 14px;
        }

        .check input {
            width: 16px;
            height: 16px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            border: 0;
            border-radius: 6px;
            padding: 0 16px;
            background: var(--brand);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover { background: var(--brand-strong); }
        .button-muted {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--line);
        }

        .button-muted:hover {
            background: #f1f5f9;
            color: var(--ink);
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border-bottom: 1px solid var(--line);
            padding: 10px 8px;
            text-align: left;
            vertical-align: top;
        }

        .table th {
            color: var(--muted);
            font-size: 13px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .form-grid .full {
            grid-column: 1 / -1;
        }

        .alert {
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            color: #1e3a8a;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 16px;
        }

        .error {
            color: var(--danger);
            font-size: 13px;
        }

        .muted {
            color: var(--muted);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .metric {
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 18px;
            background: var(--panel);
        }

        .metric strong {
            display: block;
            font-size: 30px;
            margin-top: 8px;
        }

        .list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .list li {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--line);
        }

        @media (max-width: 760px) {
            .topbar {
                padding: 0 16px;
                height: auto;
                min-height: 64px;
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
                padding-top: 14px;
                padding-bottom: 14px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @yield('body')
</body>
</html>
