<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CaseFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentUploadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3');
    }

    public function test_user_can_upload_document()
    {
        $user = User::factory()->create();
        $caseFile = CaseFile::factory()->create(['user_id' => $user->id]);
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->actingAs($user)
            ->post("/cases/{$caseFile->id}/documents", [  // Updated to match route pattern
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

        Storage::disk('s3')->assertExists($file->hashName());

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
        $user = User::factory()->create();
        $caseFile = CaseFile::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('document.exe', 1000);

        $response = $this->actingAs($user)
            ->post("/cases/{$caseFile->id}/documents", [  // Updated to match route pattern
                'document' => $file
            ]);

        $response->assertStatus(422);
    }
}
