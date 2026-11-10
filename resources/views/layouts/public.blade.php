<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ResidentialProperDocParse — AI Document Intake Extractor for Residential Property Managers')</title>
    <meta name="description" content="@yield('meta_description', 'ResidentialProperDocParse extracts fields from residential property forms, PDFs and attachments, validates data, routes exceptions, and creates maintenance requests plus vendor work orders with human approval.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #0a1f1c;
            --slate: #12322d;
            --panel: #18443c;
            --line: rgba(94, 234, 198, 0.22);
            --teal: #5eeac6;
            --teal-deep: #0d9488;
            --sand: #f6d98a;
            --fog: #e7f3ef;
            --muted: #8fb3aa;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Manrope", sans-serif;
            color: var(--fog);
            background:
                radial-gradient(1200px 600px at 10% -10%, rgba(94, 234, 198, 0.16), transparent 55%),
                radial-gradient(900px 500px at 90% 0%, rgba(246, 217, 138, 0.08), transparent 50%),
                linear-gradient(180deg, #071614 0%, #0d2420 40%, #0a1f1c 100%);
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }
        .wrap { width: min(1120px, calc(100% - 2rem)); margin: 0 auto; }
        header {
            position: sticky; top: 0; z-index: 20;
            backdrop-filter: blur(12px);
            background: rgba(7, 22, 20, 0.78);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .nav { display: flex; align-items: center; justify-content: space-between; padding: 1rem 0; gap: 1rem; }
        .brand {
            font-family: "DM Serif Display", serif;
            letter-spacing: 0.01em;
            font-size: 1.35rem;
            color: var(--teal);
        }
        .brand span { color: var(--fog); }
        .nav-links { display: flex; flex-wrap: wrap; gap: 0.9rem; align-items: center; font-size: 0.92rem; color: var(--muted); }
        .nav-links a:hover { color: var(--teal); }
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.75rem 1.15rem; border-radius: 0.35rem; font-weight: 600;
            border: 1px solid transparent; transition: 0.2s ease;
        }
        .btn-primary { background: linear-gradient(135deg, var(--teal), var(--teal-deep)); color: #04201b; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 30px rgba(13, 148, 136, 0.25); }
        .btn-ghost { border-color: var(--line); color: var(--fog); }
        .btn-ghost:hover { border-color: var(--teal); color: var(--teal); }
        .hero {
            padding: 4.5rem 0 3.5rem;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 2.5rem;
            align-items: center;
        }
        .eyebrow { color: var(--sand); font-size: 0.85rem; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; }
        h1, h2, h3 { font-family: "DM Serif Display", serif; letter-spacing: 0.01em; margin: 0.4rem 0; line-height: 1.1; }
        h1 { font-size: clamp(2.5rem, 5.5vw, 4.1rem); color: #fff; max-width: 16ch; }
        h2 { font-size: clamp(1.9rem, 3.8vw, 2.8rem); color: #fff; }
        h3 { font-size: 1.35rem; color: var(--teal); }
        .lead { color: var(--muted); font-size: 1.08rem; line-height: 1.65; max-width: 46ch; }
        .cta-row { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 1.6rem; }
        .hero-visual {
            position: relative;
            min-height: 340px;
            border: 1px solid var(--line);
            background:
                linear-gradient(160deg, rgba(94,234,198,0.12), transparent 40%),
                repeating-linear-gradient(90deg, rgba(255,255,255,0.03) 0 1px, transparent 1px 28px),
                repeating-linear-gradient(0deg, rgba(255,255,255,0.03) 0 1px, transparent 1px 28px),
                var(--panel);
            overflow: hidden;
            animation: rise 0.9s ease both;
        }
        .pulse-line {
            position: absolute; inset: 18% 12%;
            border: 1px solid rgba(94, 234, 198, 0.35);
            box-shadow: 0 0 0 1px rgba(246,217,138,0.08) inset;
            animation: pulse 2.8s ease-in-out infinite;
        }
        .doc-chip {
            position: absolute; left: 10%; top: 18%;
            background: rgba(10,31,28,0.9); border: 1px solid var(--line);
            padding: 0.85rem 1rem; width: 70%;
            animation: slideIn 1s ease 0.2s both;
        }
        .doc-chip strong { display: block; color: var(--teal); margin-bottom: 0.25rem; }
        .doc-chip small { color: var(--muted); }
        .meter {
            position: absolute; right: 10%; bottom: 16%; width: 55%;
            background: rgba(10,31,28,0.92); border: 1px solid rgba(246,217,138,0.25);
            padding: 0.9rem 1rem; animation: slideIn 1s ease 0.45s both;
        }
        .bar { height: 8px; background: #1f4a42; margin-top: 0.55rem; overflow: hidden; }
        .bar > span { display: block; height: 100%; width: 92%; background: linear-gradient(90deg, var(--teal), var(--sand)); animation: fill 1.6s ease 0.6s both; }
        section { padding: 3.5rem 0; }
        .section-head { margin-bottom: 1.75rem; }
        .grid { display: grid; gap: 1rem; }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .module {
            background: rgba(24, 68, 60, 0.55);
            border: 1px solid rgba(255,255,255,0.06);
            padding: 1.2rem 1.25rem;
            transition: border-color 0.2s ease, transform 0.2s ease;
        }
        .module:hover { border-color: var(--line); transform: translateY(-2px); }
        .badge {
            display: inline-block; font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.06em; text-transform: uppercase;
            padding: 0.25rem 0.5rem; margin-bottom: 0.55rem;
        }
        .on { background: rgba(94,234,198,0.15); color: var(--teal); }
        .off { background: rgba(255,255,255,0.06); color: var(--muted); }
        .module p { color: var(--muted); line-height: 1.55; margin: 0.4rem 0 0; font-size: 0.95rem; }
        .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.85rem; }
        .step {
            border-left: 3px solid var(--teal);
            background: rgba(18, 50, 45, 0.8);
            padding: 1rem 1rem 1.1rem;
            animation: rise 0.7s ease both;
        }
        .step:nth-child(2) { animation-delay: 0.1s; }
        .step:nth-child(3) { animation-delay: 0.2s; }
        .step:nth-child(4) { animation-delay: 0.3s; }
        .step span { color: var(--sand); font-family: "DM Serif Display", serif; font-size: 1.4rem; }
        footer {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 2rem 0 2.5rem; color: var(--muted); font-size: 0.92rem;
        }
        .price {
            border: 1px solid var(--line); padding: 1.5rem; background: rgba(24,68,60,0.4);
        }
        .price .amount { font-family: "DM Serif Display", serif; font-size: 3rem; color: var(--teal); }
        table.demo { width: 100%; border-collapse: collapse; }
        table.demo th, table.demo td { text-align: left; padding: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.08); }
        table.demo th { color: var(--teal); font-weight: 600; }
        .notice {
            border: 1px solid rgba(246,217,138,0.35);
            background: rgba(246,217,138,0.08);
            padding: 1rem 1.1rem;
            color: var(--sand);
            font-size: 0.95rem;
            line-height: 1.55;
        }
        @keyframes rise { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
        @keyframes slideIn { from { opacity: 0; transform: translateX(-12px); } to { opacity: 1; transform: none; } }
        @keyframes pulse { 0%,100% { transform: scale(1); opacity: 0.85; } 50% { transform: scale(1.02); opacity: 1; } }
        @keyframes fill { from { width: 0; } to { width: 92%; } }
        @media (max-width: 900px) {
            .hero, .grid-3, .grid-2, .steps { grid-template-columns: 1fr; }
            h1 { max-width: none; }
            .nav-links { display: none; }
        }
    </style>
    @stack('head')
</head>
<body>
<header>
    <div class="wrap nav">
        <a class="brand" href="{{ route('home') }}">Residential<span>ProperDocParse</span></a>
        <nav class="nav-links">
            <a href="{{ route('features') }}">Features</a>
            <a href="{{ route('workflow') }}">Workflow</a>
            <a href="{{ route('pricing') }}">Pricing</a>
            <a href="{{ route('demo') }}">Demo Users</a>
            <a class="btn btn-ghost" href="/admin">Admin Login</a>
        </nav>
    </div>
</header>
<main>
    @yield('content')
</main>
<footer>
    <div class="wrap">
        <strong style="color:#fff">ResidentialProperDocParse</strong> — AI Document Intake Extractor for Residential Property Managers.<br>
        Built with Laravel + Filament · Modular SaaS · Fair-housing & tenant privacy aware · Contact: <a href="https://imrandev.bd/contact" style="color:var(--teal)">imrandev.bd/contact</a>
    </div>
</footer>
</body>
</html>
