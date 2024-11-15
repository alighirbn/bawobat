<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class DynamicAppName
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Example logic: Set APP_NAME based on the route or URI
        if ($request->is('admin/*')) {
            Config::set('app.name', 'Admin Panel');
        } elseif ($request->is('user/*')) {
            Config::set('app.name', 'User Dashboard');
        } else {
            Config::set('app.name', 'شركة بوابة العلم');
        }

        return $next($request);
    }
}
