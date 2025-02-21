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
            ->post(route('documents.store', $caseFile), [
                'document' => $file,
                'title' => 'Test Document',
                'description' => 'Test Description'
            ]);

        $response->assertStatus(200);
        
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
            ->post(route('documents.store', $caseFile), [
                'document' => $file
            ]);

        $response->assertStatus(422);
    }
}