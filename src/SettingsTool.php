<?php

namespace Bakerkretzmar\NovaSettingsTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class SettingsTool extends Tool
{
    public function boot()
    {
        Nova::script('settings-tool', __DIR__ . '/../dist/js/tool.js');
    }

    public function renderNavigation()
    {
        return view('settings-tool::navigation');
    }
}
