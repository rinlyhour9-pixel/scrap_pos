<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ config('app.name','Scrap POS') }}</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<style>
    .language-control{display:inline-flex;align-items:center;gap:3px;padding:4px;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:11px;box-shadow:0 1px 2px rgb(15 23 42 / 6%)}
    .language-label{display:grid;place-items:center;width:28px;color:#64748b;font-size:15px}
    .language-option{border:0;border-radius:7px;background:transparent;color:#64748b;padding:6px 10px;font-family:Inter,Battambang,sans-serif;font-size:12px;font-weight:700;line-height:1.2;transition:all .18s ease}
    .language-option:hover{color:#1d4ed8;background:#dbeafe}.language-option.active{background:#fff;color:#1d4ed8;box-shadow:0 1px 3px rgb(15 23 42 / 14%)}
    .language-switch{display:none!important}
    @media(max-width:600px){.language-label{display:none}.language-option{padding:6px 8px}}
</style>
</head>
<body>
<div class="app-shell" id="app-shell">
<aside class="sidebar" id="app-sidebar">
<a class="brand" href="{{ route('dashboard') }}">
<span>♻</span>
<div>RIN LY HOUR<small>Scrap POS</small>
</div>
</a>
<nav>
<a href="{{ route('dashboard') }}">⌂ {{ __('pos.dashboard') }}</a>
<p>{{ __('pos.pos') }}</p>
<a href="{{ route('pos.buy') }}">↓ {{ __('pos.buy_scrap') }}</a>
<a href="{{ route('pos.sell') }}">↑ {{ __('pos.sell_scrap') }}</a>
<p>{{ __('pos.management') }}</p>
<a href="{{ route('purchases.index') }}">{{ __('pos.purchases') }}</a>
<a href="{{ route('sales.index') }}">{{ __('pos.sales') }}</a>
<a href="{{ route('inventory.index') }}">{{ __('pos.inventory') }}</a>
<a href="{{ route('inventory.movements') }}">{{ __('pos.stock_movement') }}</a>
<a href="{{ route('materials.index') }}">{{ __('pos.materials') }}</a>
<a href="{{ route('customers.index') }}">{{ __('pos.customers') }}</a>
<a href="{{ route('suppliers.index') }}">{{ __('pos.suppliers') }}</a>
<a href="{{ route('expenses.index') }}">{{ __('pos.expenses') }}</a>
<a href="{{ route('reports.index') }}">{{ __('pos.reports') }}</a>
</nav>
<form method="post" action="{{ route('logout') }}">
@csrf<button class="logout">{{ __('pos.logout') }}</button>
</form>
</aside>
<main>
<header>
<button class="menu" type="button" id="sidebar-toggle" aria-label="Toggle sidebar" aria-controls="app-sidebar" aria-expanded="true">
    <span aria-hidden="true">☰</span>
</button>
<div>
<strong>{{ __('pos.welcome') }}</strong>
<span class="text-muted">{{ now()->format('l, d M Y') }}</span>
</div>
<div class="d-flex align-items-center gap-3">
<form method="post" class="language-control" aria-label="{{ __('pos.language') }}">
<button type="submit" formaction="{{ route('language.switch', 'en') }}" class="language-option {{ app()->getLocale() === 'en' ? 'active' : '' }}">{{ __('pos.english') }}</button>
<button type="submit" formaction="{{ route('language.switch', 'km') }}" class="language-option {{ app()->getLocale() === 'km' ? 'active' : '' }}">{{ __('pos.khmer') }}</button>
@csrf<button class="language-switch" title="{{ __('pos.language') }}">{{ app()->getLocale() === 'en' ? 'ážáŸ’áž˜áŸ‚ážš' : 'EN' }}</button>
</form>
<div class="avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
</div>
</header>
<section class="content {{ request()->routeIs('pos.*') ? 'pos-content' : ''}}">@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif @yield('content')</section>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>
<script>
    (() => {
        const shell = document.getElementById('app-shell');
        const sidebar = document.querySelector('.sidebar');
        const toggle = document.getElementById('sidebar-toggle');
        const mobile = window.matchMedia('(max-width: 1000px)');

        const updateToggle = () => {
            const expanded = mobile.matches
                ? sidebar.classList.contains('sidebar-open')
                : !shell.classList.contains('sidebar-collapsed');

            toggle.setAttribute('aria-expanded', String(expanded));
            toggle.setAttribute('aria-label', expanded ? 'Close sidebar' : 'Open sidebar');
            toggle.querySelector('span').textContent = expanded ? '×' : '☰';
        };

        toggle.addEventListener('click', () => {
            if (mobile.matches) {
                sidebar.classList.toggle('sidebar-open');
            } else {
                shell.classList.toggle('sidebar-collapsed');
            }
            updateToggle();
        });

        mobile.addEventListener('change', updateToggle);
        updateToggle();
    })();
</script>@stack('scripts')</body>
</html>


