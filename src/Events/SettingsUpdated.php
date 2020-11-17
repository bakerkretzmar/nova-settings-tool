<?php

namespace Bakerkretzmar\NovaSettingsTool\Events;

class SettingsUpdated
{
    public $settings;
    public $oldSettings;

    public function __construct(array $settings, array $oldSettings)
    {
        $this->settings = $settings;
        $this->oldSettings = $oldSettings;
    }
}
