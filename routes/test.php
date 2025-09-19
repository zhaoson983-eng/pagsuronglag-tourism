<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'token' => csrf_token(),
        'session_data' => session()->all()
    ]);
});

Route::post('/test-csrf', function () {
    return response()->json([
        'message' => 'CSRF token is valid!',
        'session_id' => session()->getId()
    ]);
});
