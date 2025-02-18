<?php

use App\Http\Controllers\TranscriptionController;
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
            'caseFiles' => auth()->user()->caseFiles()->latest()->get()
        ]);
    })->name('dashboard');

    Route::resource('case-files', \App\Http\Controllers\CaseFileController::class);
    Route::resource('case-files.drafts', \App\Http\Controllers\DraftController::class);
});

Route::post('/transcribe', [TranscriptionController::class, 'transcribe'])
    ->name('transcribe')
    ->middleware(['web', 'auth:sanctum']);
