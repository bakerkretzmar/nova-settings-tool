<?php

namespace Bakerkretzmar\NovaSettingsTool;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class SettingsTool extends Tool
{
    public function boot()
    {
        Nova::script('nova-settings-tool', __DIR__ . '/../dist/js/tool.js');
    }

    public function menu(Request $request)
    {
        return MenuSection::make(__(config('nova-settings-tool.sidebar-label', 'Settings')))
            ->path('/settings')
            ->icon('cog');
    }
}
