<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $requested = $request->query('lang');

        if (in_array($requested, ['fr', 'en'], true)) {
            session(['locale' => $requested]);
        }

        App::setLocale(session('locale', config('app.locale')));

        return $next($request);
    }
}
