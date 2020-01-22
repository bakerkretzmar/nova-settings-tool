<?php

namespace Andreasgj\NovaSettingsTool\Http\Middleware;

use Andreasgj\NovaSettingsTool\SettingsTool;

class Authorize
{
    public function handle($request, $next)
    {
        return resolve(SettingsTool::class)->authorize($request)
            ? $next($request)
            : abort(403);
    }
}
