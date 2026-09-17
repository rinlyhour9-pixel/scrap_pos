<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ __('pos.sign_in') }} · Scrap POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-wrap">
        <form class="login-language" method="post"
            action="{{ route('language.switch', app()->getLocale() === 'en' ? 'km' : 'en') }}">
            @csrf<button>{{ app()->getLocale() === 'en' ? 'ខ្មែរ' : 'EN' }}</button></form>
        <form class="login-card" method="post" action="{{ route('login.attempt') }}">@csrf<div class="login-logo">♻
            </div>
            <h2>Rin Ly Hour</h2>
            <p>Scrap Dealer Management</p><label>{{ __('pos.email') }}</label><input class="form-control" type="email"
                name="email" value="{{ old('email') }}" required autofocus><label
                class="mt-3">{{ __('pos.password') }}</label><input class="form-control" type="password"
                name="password" required><label class="mt-3 small"><input type="checkbox" name="remember">
                {{ __('pos.remember') }}</label><button
                class="btn btn-primary w-100 mt-4">{{ __('pos.sign_in') }}</button><small
                class="d-block text-center mt-3 text-muted">{{ __('pos.development_login') }}</small>
        </form>
    </div>
</body>

</html>
