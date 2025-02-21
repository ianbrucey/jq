<?php

use App\Http\Controllers\CaseFileDocumentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\OpenAiProjectController;
use App\Http\Controllers\TranscriptionController;
use App\Http\Livewire\EnhancedApiTokenManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
    Route::get('/dashboard', function (Request $request) {
        $search = $request->input('search');
        $query = auth()->user()->caseFiles()->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('case_number', 'like', "%{$search}%")
                  ->orWhere('desired_outcome', 'like', "%{$search}%");
            });
        }

        return view('dashboard', [
            'caseFiles' => $query->paginate(5)->withQueryString(),
            'search' => $search
        ]);
    })->name('dashboard');

    Route::resource('case-files', \App\Http\Controllers\CaseFileController::class);
    Route::resource('case-files.drafts', \App\Http\Controllers\DraftController::class);
});

Route::post('/transcribe', [TranscriptionController::class, 'transcribe'])
    ->name('transcribe')
    ->middleware(['web', 'auth:sanctum']);

Route::get('/case-files/{caseFile}/documents', [CaseFileDocumentController::class, 'index'])
    ->name('case-files.documents.index');

Route::post('/cases/{caseFile}/documents', [DocumentController::class, 'store'])
    ->name('documents.store');

Route::middleware(['auth', 'can:manage-project-tokens'])->group(function () {
    Route::get('/manage-project-tokens', [OpenAiProjectController::class, 'index'])->name('openai.projects.index');
    Route::resource('openai/projects', OpenAiProjectController::class)
        ->except('index')
        ->names([
            'create' => 'openai.projects.create',
            'store' => 'openai.projects.store',
            'show' => 'openai.projects.show',
            'edit' => 'openai.projects.edit',
            'update' => 'openai.projects.update',
            'destroy' => 'openai.projects.destroy'
        ]);
});
