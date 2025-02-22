<?php

namespace App\Console\Commands;

use App\Models\OpenAiProject;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ManageOpenAiProjects extends Command
{
    protected $signature = 'openai:projects {action=sync : Action to perform (sync/list)}';
    protected $description = 'Manage OpenAI projects';

    public function handle()
    {
        $action = $this->argument('action');

        if ($action === 'list') {
            $this->listProjects();
            return;
        }

        if ($action === 'sync') {
            $this->syncProjects();
            return;
        }

        $this->error("Unknown action: {$action}");
    }

    private function listProjects()
    {
        $projects = OpenAiProject::all();
        
        $this->table(
            ['Name', 'API Key', 'Organization ID', 'Active', 'Storage Used'],
            $projects->map(fn($p) => [
                $p->name,
                substr($p->api_key, 0, 7) . '...',
                $p->organization_id,
                $p->is_active ? 'Yes' : 'No',
                $p->storage_used
            ])
        );
    }

    private function syncProjects()
    {
        $this->call('db:seed', [
            '--class' => 'OpenAiProjectSeeder'
        ]);
    }
}