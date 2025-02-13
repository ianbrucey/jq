<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/test', function () {
    return view('test');
})->name('testr');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'cases' => auth()->user()->cases()->latest()->get()
        ]);
    })->name('dashboard');

    Route::resource('cases', \App\Http\Controllers\CaseController::class);
    Route::resource('cases.drafts', \App\Http\Controllers\DraftController::class);
});
