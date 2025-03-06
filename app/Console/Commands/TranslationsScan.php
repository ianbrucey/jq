<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TranslationsScan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan project files for hardcoded strings to extract for translations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $paths = [
            base_path('resources/views'),
            base_path('app'),
            base_path('resources/js'),
        ];

        $results = [];

        foreach ($paths as $path) {
            if (!File::exists($path)) {
                continue;
            }

            $files = File::allFiles($path);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $extension = $file->getExtension();

                // Filter only Blade, PHP, or JS files (Blade templates usually have "blade.php" in their file name)
                if (!in_array($extension, ['php', 'js']) && !Str::contains($filename, 'blade.php')) {
                    continue;
                }

                $content = $file->getContents();

                // Pattern 1: Alpine x-text directives, e.g. x-text="'Sample Text'"
                preg_match_all('/x-text="\s*\'([^\']+)\'\s*"/', $content, $matches);
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $match) {
                        $results[] = [
                            'file' => $file->getRelativePathname(),
                            'context' => 'Alpine x-text',
                            'original' => $match,
                            'suggested_key' => $this->suggestKey($file, $match),
                        ];
                    }
                }

                // Pattern 2: Blade plain text between HTML tags
                // Note: This is a naive regex that extracts text between ">" and "<". You might want to fine-tune it.
                preg_match_all('/>([^<>{}]+)</', $content, $matches);
                if (!empty($matches[1])) {
                    foreach ($matches[1] as $match) {
                        $clean = trim($match);
                        if ($clean && strlen($clean) > 3) { // minimal length filter
                            $results[] = [
                                'file' => $file->getRelativePathname(),
                                'context' => 'Blade plain text',
                                'original' => $clean,
                                'suggested_key' => $this->suggestKey($file, $clean),
                            ];
                        }
                    }
                }
            }
        }

        // Generate structured JSON output.
        $output = json_encode($results, JSON_PRETTY_PRINT);
        $outputPath = storage_path('app/translation_scan_results.json');
        File::put($outputPath, $output);

        $this->info("Scan complete. Found " . count($results) . " translatable strings. Output saved to {$outputPath}");
        return 0;
    }

    /**
     * Suggests a translation key based on the file name and a snippet of the original text.
     *
     * @param \SplFileInfo $file
     * @param string $text
     * @return string
     */
    protected function suggestKey($file, $text)
    {
        $basename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        $snippet = Str::slug(substr($text, 0, 20));
        return strtolower($basename . '.' . $snippet);
    }
}