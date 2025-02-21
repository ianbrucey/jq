<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class TrackApiTokenUsage
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->user() && $request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            
            if ($token) {
                $token->usage()->create([
                    'endpoint' => $request->path(),
                    'method' => $request->method(),
                    'ip_address' => $request->ip(),
                    'response_code' => $response->status()
                ]);
            }
        }

        return $response;
    }
}