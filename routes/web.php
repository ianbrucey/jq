<?php

use App\Http\Controllers\CaseFileDocumentController;
use App\Http\Controllers\CorrespondenceController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\OpenAiProjectController;
use App\Http\Controllers\TranscriptionController;
use App\Livewire\EnhancedApiTokenManager;
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

// AUTHENTICATED USER ROUTES
Route::middleware(['auth'])->group(function () {
    Route::post('/transcribe', [TranscriptionController::class, 'transcribe'])
        ->name('transcribe')
        ->middleware(['web', 'auth:sanctum']);

    Route::resource('case-files.documents', CaseFileDocumentController::class)
        ->only(['index', 'store']);

    // Updated correspondence route to fetch the CaseFile model
    Route::get('/case-files/{caseFile}/correspondences', function(App\Models\CaseFile $caseFile) {
        return view('case-files.correspondences.index', ['caseFile' => $caseFile]);
    })->name('case-files.correspondences.index');

    Route::get('/case-files/{caseFile}/correspondences/{thread}', [CorrespondenceController::class, 'show'])
        ->name('case-files.correspondences.show');

    Route::get('/invitations', function () {
        return view('invitations.index');
    })->name('invitations');
});
// AUTHENTICATED USER ROUTES


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

Route::get('/documents/{document}/download', function (Document $document) {
    return Storage::download($document->file_path, $document->original_filename);
})->name('documents.download');

Route::get('/documents/{document}/show', [DocumentController::class, 'show'])
    ->name('document.show')
    ->middleware(['signed', 'auth']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/address-book', function () {
        return view('address-book.index');
    })->name('address-book.index');
});
