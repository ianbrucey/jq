<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CaseFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Contracts\Filesystem\Filesystem;

class DocumentUploadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        /** @var Filesystem $storage */
        $storage = Storage::fake('s3');
    }

    public function test_user_can_upload_document()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $caseFile = CaseFile::factory()->create(['user_id' => $user->id]);
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->actingAs($user)
            ->post("/case-files/{$caseFile->id}/documents", [
                'document' => $file,
                'title' => 'Test Document',
                'description' => 'Test Description'
            ]);

//        dd($response->);

        if ($response->status() === 500) {
            dd($response->exceptions);
            dump($response->exception->getTraceAsString());
        }

        $response->assertStatus(201); // Note: Changed to 201 as DocumentController returns 201 for successful creation

        $storage->assertExists('documents/' . $file->hashName());

        $this->assertDatabaseHas('documents', [
            'case_file_id' => $caseFile->id,
            'original_filename' => 'document.pdf',
            'title' => 'Test Document',
            'description' => 'Test Description',
            'ingestion_status' => 'pending'
        ]);
    }

    public function test_rejects_invalid_file_types()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $caseFile = CaseFile::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('document.exe', 1000);

        $response = $this->actingAs($user)
            ->post("/case-files/{$caseFile->id}/documents", [
                'document' => $file
            ]);

        $response->assertStatus(422);
    }
}
