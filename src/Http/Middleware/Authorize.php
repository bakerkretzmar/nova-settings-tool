<?php

namespace Bakerkretzmar\SettingsTool\Http\Middleware;

use Bakerkretzmar\SettingsTool\SettingsTool;

class Authorize
{
    /**
     * Handle the incoming request.
     */
    public function handle($request, $next)
    {
        return resolve(SettingsTool::class)->authorize($request) ? $next($request) : abort(403);
    }
}
