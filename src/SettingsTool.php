<?php

namespace Bakerkretzmar\SettingsTool;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class SettingsTool extends Tool
{
    public function __construct(string $title = null)
    {
        parent::__construct();
    }

    /**
     * Perform any tasks that need to happen when the tool is booted.
     */
    public function boot()
    {
        Nova::script('settings-tool', __DIR__.'/../dist/js/tool.js');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     */
    public function renderNavigation()
    {
        return view('settings-tool::navigation');
    }
}
