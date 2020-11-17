<?php

use Bakerkretzmar\NovaSettingsTool\Http\Controllers\SettingsToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SettingsToolController::class, 'read']);
Route::post('/', [SettingsToolController::class, 'write']);
