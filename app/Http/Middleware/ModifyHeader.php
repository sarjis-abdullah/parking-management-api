<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModifyHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (empty($request->headers->get('Content-Type'))) {
            $request->headers->set('Content-Type', 'application/json');
        }
        // if ($request->headers->get('Accept') == '*/*') {
        if (false !== strpos($request->headers->get('Accept'), '*/*')) {
            $request->headers->set('Accept', 'application/json');
        }
        $response = $next($request);
        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set("Access-Control-Allow-Headers", "Origin, Content-Type, Accept, Authorization, X-Requested-With, X-localization");
        $response->headers->set("Access-Control-Allow-Methods", "OPTIONS, HEAD, GET, POST, PUT, DELETE");

        $response->headers->set("Strict-Transport-Security", "max-age=31536000; includeSubDomains");

        return $response;
    }
}
