<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;
class Authenticate { public function handle(Request $request, Closure $next): mixed { return Auth::check() ? $next($request) : redirect()->route('login'); } }
