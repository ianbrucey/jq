<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class TranslationsApply extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:apply {--dry-run : Run the command in dry run mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply translation replacements to source files based on scan results';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        // Load translation scan results
        $scanResultsPath = storage_path('app/translation_scan_results.json');
        if (!File::exists($scanResultsPath)) {
            $this->error("Scan results file not found: $scanResultsPath");
            return 1;
        }
        $results = json_decode(File::get($scanResultsPath), true);
        if ($results === null) {
            $this->error("Failed to parse JSON from $scanResultsPath");
            return 1;
        }
        $this->info("Loaded " . count($results) . " scan results.");

        // Process each scan result entry
        foreach ($results as $entry) {
            $filePath = base_path($entry['file']);
            if (!File::exists($filePath)) {
                $this->warn("File not found: " . $entry['file']);
                continue;
            }
            $fileContent = File::get($filePath);

            $original = $entry['original'];
            $suggestedKey = $entry['suggested_key'];
            $translationCall = "{{ __('" . $suggestedKey . "') }}";

            // For Blade files, perform auto-replacement of isolated hardcoded strings
            if (strpos($filePath, '.blade.php') !== false && trim($original) !== '') {
                if (strpos($fileContent, $original) !== false) {
                    $this->info("Replacing in {$entry['file']}: '$original' with '$translationCall'");
                    if (!$dryRun) {
                        $newContent = str_replace($original, $translationCall, $fileContent);
                        File::put($filePath, $newContent);
                    }
                }
            } else {
                // For files not clearly safe for auto-replacement,
                // flag the entry for manual review.
                $this->line("Flagging dynamic or non-Blade file for manual review: " . $entry['file']);
            }
        }

        $this->info($dryRun ? "Dry run complete. No files were modified." : "Translation replacements applied successfully.");

        // Update translation files in lang/en/ and lang/es/ should be implemented here.
        // For brevity, this part is left as an exercise.

        return 0;
    }
}