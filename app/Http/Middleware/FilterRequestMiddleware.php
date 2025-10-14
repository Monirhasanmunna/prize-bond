<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        if(!$request->query->has('status')) {
//            $request->query->add(['status' => STATUS_ACTIVE]);
//        }
//
//        if($request->query('status') == STATUS_ALL) {
//            $request->query->remove('status');
//        }

        return $next($request);
    }
}
