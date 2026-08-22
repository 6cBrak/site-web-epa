<?php

use App\Http\Controllers\AssistantController;
use Illuminate\Support\Facades\Route;

Route::post('/assistant/message', [AssistantController::class, 'handleMessage'])
    ->middleware('throttle:20,1');

Route::get('/assistant/history', [AssistantController::class, 'history'])
    ->middleware('throttle:30,1');
