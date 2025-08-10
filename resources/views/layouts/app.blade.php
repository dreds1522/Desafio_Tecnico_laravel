<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'NewsAPI Blog')</title>
    <meta name="description" content="@yield('meta_description', 'Busque notícias e explore artigos')" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            /* slate-900 */
            --card: #111827;
            /* gray-900 */
            --muted: #9ca3af;
            /* gray-400 */
            --ring: #3b82f6;
            /* blue-500 */
            --text: #e5e7eb;
            /* gray-200 */
            --brand: #60a5fa;
            /* blue-400 */
            --brand-2: #93c5fd;
            /* blue-300 */
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            color: var(--text);
            background: radial-gradient(1200px 600px at 30% -10%, rgba(59, 130, 246, 0.25), transparent 60%),
                radial-gradient(1200px 600px at 80% 10%, rgba(99, 102, 241, 0.2), transparent 60%),
                var(--bg);
            line-height: 1.6;
        }

        a {
            color: var(--brand-2);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            gap: 12px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
        }

        .brand .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            box-shadow: 0 0 18px rgba(96, 165, 250, 0.8);
        }

        nav a {
            margin-left: 16px;
            font-weight: 600;
            color: var(--text);
            opacity: .9;
        }

        nav a:hover {
            opacity: 1;
        }

        .hero {
            text-align: center;
            padding: 64px 0 32px;
        }

        .hero h1 {
            margin: 0 0 12px;
            font-size: clamp(24px, 4vw, 38px);
            line-height: 1.15;
        }

        .hero p {
            margin: 0 auto 24px;
            color: var(--muted);
            max-width: 720px;
        }

        .search {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            max-width: 720px;
            margin: 0 auto;
            background: rgba(17, 24, 39, 0.7);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 14px;
            padding: 10px;
            backdrop-filter: blur(6px);
        }

        .search input[type="text"] {
            background: #0b1220;
            color: var(--text);
            border: 1px solid rgba(148, 163, 184, 0.2);
            padding: 14px 14px;
            border-radius: 10px;
            outline: none;
            width: 100%;
        }

        .search input[type="text"]:focus {
            border-color: var(--ring);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, .15);
        }

        .btn {
            padding: 12px 18px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            color: #0b1220;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
        }

        .grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            margin-top: 28px;
        }

        @media (min-width: 640px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .card {
            background: rgba(17, 24, 39, 0.7);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 14px;
            padding: 16px;
            transition: transform .15s ease, box-shadow .2s ease, border-color .15s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            border-color: rgba(148, 163, 184, 0.35);
        }

        .card h3 {
            margin: 0 0 8px;
            font-size: 18px;
            line-height: 1.3;
        }

        .card .meta {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .empty {
            text-align: center;
            color: var(--muted);
            padding: 22px;
            border: 1px dashed rgba(148, 163, 184, 0.25);
            border-radius: 14px;
            background: rgba(17, 24, 39, 0.45);
        }

        footer {
            padding: 28px 0;
            color: var(--muted);
            font-size: 14px;
            text-align: center;
        }

        .pager nav svg {
            width: 14px;
            height: 14px;
        }

        /* setas menores */
        .pager nav a,
        .pager nav span {
            padding: 6px 10px;
        }

        /* menos padding */
        .pager nav a {
            border-radius: 8px;
        }
    </style>
    @stack('head')
</head>

<body>
    <div class="container">
        <header>
            <div class="brand">
                <span class="dot"></span>
                <span>NewsAPI Blog</span>
            </div>
            <nav>
                <a href="{{ route('search.index') }}">Início</a>
                <a href="{{ route('searches.index') }}">Minhas buscas</a>
            </nav>
        </header>

        <main>
            @yield('content')
        </main>

        <footer>
            <span>Feito com Laravel + Blade • <a href="https://newsapi.org/" target="_blank" rel="noopener">NewsAPI</a></span>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>