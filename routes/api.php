<?php

use Bakerkretzmar\SettingsTool\Http\Controllers\SettingsToolController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [SettingsToolController::class, 'read']);
Route::post('/', [SettingsController::class, 'write']);
