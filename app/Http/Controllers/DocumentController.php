<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function show(Document $document): StreamedResponse
    {
        $this->authorize('view', $document);

        return Storage::response($document->storage_path, $document->original_filename, [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => 'inline; filename="' . $document->original_filename . '"'
        ]);
    }
}
