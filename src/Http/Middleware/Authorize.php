<?php

namespace Bakerkretzmar\NovaSettingsTool\Http\Middleware;

use Bakerkretzmar\NovaSettingsTool\SettingsTool;

class Authorize
{
    public function handle($request, $next)
    {
        return resolve(SettingsTool::class)->authorize($request)
            ? $next($request)
            : abort(403);
    }
}
