<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Mp3;

class TranscriptionController extends Controller
{
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

            // Verify the output file exists and is not empty
            if (!file_exists($outputPath) || filesize($outputPath) === 0) {
                throw new \RuntimeException("Conversion failed: Output file is missing or empty");
            }
        } catch (\Exception $e) {
            Log::error('FFmpeg conversion error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_path' => $inputPath,
                'output_path' => $outputPath,
                'ffmpeg_path' => env('FFMPEG_PATH'),
                'ffprobe_path' => env('FFPROBE_PATH')
            ]);
            throw $e;
        }
    }

    public function transcribe(Request $request)
    {
        Log::info('PHP Upload Configuration', [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_file_uploads' => ini_get('max_file_uploads'),
            'max_execution_time' => ini_get('max_execution_time'),
        ]);

        Log::info('Transcription request received', [
            'content_type' => $request->header('Content-Type'),
            'file_present' => $request->hasFile('audio'),
            'all_inputs' => $request->all(),
            'files' => $request->allFiles(),
            'headers' => $request->headers->all()
        ]);

        try {
            $request->validate([
                'audio' => 'required|file|mimes:wav,mp3,m4a,mp4,webm,ogg|max:25600' // Adding max file size (25MB)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'validator' => $e->validator->failed(),
                'file_upload_errors' => [
                    'error_code' => $_FILES['audio']['error'] ?? 'no_file',
                    'error_message' => $this->getUploadErrorMessage($_FILES['audio']['error'] ?? null)
                ]
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Validation failed: ' . implode(', ', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $e->errors()))
            ], 422);
        }

        try {
            $file = $request->file('audio');
            Log::info('File details', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);

            $originalPath = $file->store('temp', 'local');
            $fullOriginalPath = Storage::path($originalPath);

            // Check if the file is already an MP3
            $mimeType = mime_content_type($fullOriginalPath);
            $needsConversion = $mimeType !== 'audio/mpeg' && $mimeType !== 'audio/mp3';

            Log::info('File analysis', [
                'detected_mime_type' => $mimeType,
                'needs_conversion' => $needsConversion,
                'original_path' => $originalPath
            ]);

            $pathForWhisper = $fullOriginalPath;

            if ($needsConversion) {
                $mp3Path = Storage::path('temp/converted_' . uniqid() . '.mp3');
                Log::info('Converting to MP3', ['target_path' => $mp3Path]);

                try {
                    $this->convertToMp3($fullOriginalPath, $mp3Path);
                    $pathForWhisper = $mp3Path;
                    Log::info('Conversion successful');
                } catch (\Exception $e) {
                    Log::error('Conversion failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }

                // Delete the original file as we don't need it anymore
                Storage::delete($originalPath);
            }

            Log::info('Calling OpenAI Whisper API');
            $response = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($pathForWhisper, 'r'),
                'response_format' => 'text'
            ]);
            Log::info('OpenAI response received', ['response' => $response->text]);

            // Clean up files
            if ($needsConversion) {
                unlink($mp3Path);
            } else {
                Storage::delete($originalPath);
            }

            return response()->json([
                'success' => true,
                'transcription' => $response->text
            ]);

        } catch (\Exception $e) {
            Log::error('Transcription failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clean up files in case of error
            if (isset($originalPath) && Storage::exists($originalPath)) {
                Storage::delete($originalPath);
            }
            if (isset($mp3Path) && file_exists($mp3Path)) {
                unlink($mp3Path);
            }

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getUploadErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'A PHP extension stopped the file upload';
            default:
                return 'Unknown upload error';
        }
    }
}
