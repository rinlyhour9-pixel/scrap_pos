<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
class SetLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        App::setLocale($request->session()->get('locale', config('app.locale', 'en')));
        return $next($request);
    }
}
