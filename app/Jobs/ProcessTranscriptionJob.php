<?php

namespace App\Jobs;

use App\Events\TranscriptionCompleted;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;

class ProcessTranscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [30, 60, 120]; // Retry after 30s, 60s, then 120s
    public $timeout = 600; // 10 minutes

    private $filePath;
    private $originalName;
    private $userId;
    public $jobId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, string $originalName, int $userId)
    {
        $this->filePath = $filePath;
        $this->originalName = $originalName;
        $this->userId = $userId;
        $this->jobId = uniqid('job_', true);
    }

    private function convertToMp3($inputPath, $outputPath)
    {
        try {
            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => env('FFMPEG_PATH', '/opt/homebrew/bin/ffmpeg'),
                'ffprobe.binaries' => env('FFPROBE_PATH', '/opt/homebrew/bin/ffprobe'),
                'timeout'          => 3600,
                'ffmpeg.threads'   => 12,
            ]);

            $audio = $ffmpeg->open($inputPath);
            $format = new Mp3();

            $format
                ->setAudioChannels(1)
                ->setAudioKiloBitrate(128);

            $audio->save($format, $outputPath);

            if (!file_exists($outputPath) || filesize($outputPath) === 0) {
                throw new \RuntimeException("Conversion failed: Output file is missing or empty");
            }
        } catch (\Exception $e) {
            Log::error('FFmpeg conversion error in job', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_path' => $inputPath,
                'output_path' => $outputPath,
                'job_id' => $this->jobId
            ]);
            throw $e;
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // First verify the file exists
        if (!Storage::disk('local')->exists($this->filePath)) {
            Log::error('Audio file missing', [
                'job_id' => $this->jobId,
                'file_path' => $this->filePath
            ]);
            throw new \RuntimeException("Audio file not found: {$this->filePath}");
        }

        $fullOriginalPath = Storage::disk('local')->path($this->filePath);

        try {
            // Check if the file is already an MP3
            $mimeType = mime_content_type($fullOriginalPath);
            $needsConversion = $mimeType !== 'audio/mpeg' && $mimeType !== 'audio/mp3';

            Log::info('Processing transcription job', [
                'job_id' => $this->jobId,
                'file_path' => $this->filePath,
                'mime_type' => $mimeType,
                'needs_conversion' => $needsConversion
            ]);

            $pathForWhisper = $fullOriginalPath;

            if ($needsConversion) {
                $mp3Path = Storage::disk('local')->path('temp/converted_' . uniqid() . '.mp3');
                Log::info('Converting to MP3', [
                    'target_path' => $mp3Path,
                    'job_id' => $this->jobId
                ]);

                $this->convertToMp3($fullOriginalPath, $mp3Path);
                $pathForWhisper = $mp3Path;

                // Delete the original file as we don't need it anymore
                Storage::disk('local')->delete($this->filePath);
            }

            Log::info('Calling OpenAI Whisper API', [
                'job_id' => $this->jobId
            ]);

            $response = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($pathForWhisper, 'r'),
                'response_format' => 'text'
            ]);

            // Store the transcription result
            $resultPath = 'transcriptions/' . $this->userId . '/' . date('Y/m/d') . '/' . $this->jobId . '.txt';
            Storage::disk('local')->put($resultPath, $response->text);

            Log::info('Transcription completed', [
                'job_id' => $this->jobId,
                'result_path' => $resultPath,
                'transcription' => $response->text
            ]);

            // Clean up files
            if ($needsConversion) {
                unlink($mp3Path);
            } else {
                Storage::disk('local')->delete($this->filePath);
            }

            // In the handle() method, replace the commented event line with:
            event(new TranscriptionCompleted($this->userId, $response->text));

        } catch (\Exception $e) {
            Log::error('Transcription job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'job_id' => $this->jobId,
                'file_path' => $this->filePath
            ]);

            // Clean up files in case of error
            if (isset($this->filePath) && Storage::disk('local')->exists($this->filePath)) {
                Storage::disk('local')->delete($this->filePath);
            }
            if (isset($mp3Path) && file_exists($mp3Path)) {
                unlink($mp3Path);
            }

            throw $e; // This will trigger job retry if attempts remain
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Transcription job failed finally', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'job_id' => $this->jobId,
            'file_path' => $this->filePath
        ]);

        // Clean up any remaining files
        if (Storage::disk('local')->exists($this->filePath)) {
            Storage::disk('local')->delete($this->filePath);
        }

        // Here you could notify the user about the failure
        // event(new TranscriptionFailed($this->userId, $this->originalName));
    }
}
