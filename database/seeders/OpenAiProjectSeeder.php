<?php

namespace Database\Seeders;

use App\Models\OpenAiProject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class OpenAiProjectSeeder extends Seeder
{
    public function run(): void
    {
        $configPath = config_path('openai-projects.json');

        if (!File::exists($configPath)) {
            $this->command->warn('OpenAI projects configuration file not found.');
            return;
        }

        $config = json_decode(File::get($configPath), true);

        if (!isset($config['projects']) || empty($config['projects'])) {
            $this->command->warn('No OpenAI projects configured.');
            return;
        }

        // Get default organization from services config
        $defaultOrganization = config('services.openai.organization');

        foreach ($config['projects'] as $project) {
            if (OpenAiProject::where('name', $project['name'])->exists()) {
                $this->command->info("Project '{$project['name']}' already exists, skipping.");
                continue;
            }

            OpenAiProject::create([
                'name' => $project['name'],
                'api_key' => $project['api_key'],
                'organization_id' => $defaultOrganization,
                'is_active' => $project['is_active'] ?? true,
                'storage_used' => 0
            ]);

            $this->command->info("Created project: {$project['name']}");
        }
    }
}
