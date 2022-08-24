<?php

namespace Trov\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForceTrailingSlash
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $currentPath = $request->getRequestUri();

        if ($currentPath !== '/' && ! Str::of($currentPath)->contains('/admin/') && ! Str::of($currentPath)->contains('?')) {
            if (! preg_match('/.+\/$/', $currentPath)) {
                $base_url = config('app.url');

                return redirect($base_url . $currentPath . '/');
            }
        }

        return $next($request);
    }
}
