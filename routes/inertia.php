<?php

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Http\Requests\NovaRequest;

Route::get('/', function (NovaRequest $request) {
    return inertia('NovaSettingsTool');
});
