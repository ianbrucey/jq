<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CaseAccessLog;

class LogCaseAccess
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($case = $request->route('case')) {
            CaseAccessLog::create([
                'case_file_id' => $case->id,
                'user_id' => $request->user()->id,
                'action' => $request->method() . ' ' . $request->path(),
                'metadata' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'access_level' => $request->get('access_level')
                ]
            ]);
        }

        return $response;
    }
}