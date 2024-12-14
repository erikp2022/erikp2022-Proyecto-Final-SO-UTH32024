<?php

namespace App\Http\Middleware;

use Closure;

class InstallationChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $installCheck = config('devstar.installed');

        if ($installCheck == false){
            return redirect('/install');
        }
        return $next($request);
    }
}
