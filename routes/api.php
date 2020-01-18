<?php

use Bakerkretzmar\NovaSettingsTool\Http\Controllers\SettingsToolController;

Route::get('/fields', [SettingsToolController::class, 'fields']);
Route::post('/', [SettingsToolController::class, 'write']);
