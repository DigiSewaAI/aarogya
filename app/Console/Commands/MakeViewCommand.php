<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeViewCommand extends Command
{
    protected $signature = 'make:view {name} {--extension=blade.php}';
    protected $description = 'Create a new view file';

    public function handle()
    {
        $name = $this->argument('name');
        $extension = $this->option('extension');
        
        // Replace dot with directory separator (e.g., 'auth.login' -> 'auth/login')
        $path = str_replace('.', '/', $name);
        $fullPath = resource_path("views/{$path}.{$extension}");
        
        $directory = dirname($fullPath);
        
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        if (File::exists($fullPath)) {
            $this->error("View already exists: {$fullPath}");
            return 1;
        }
        
        File::put($fullPath, "{{-- View: {$name} --}}\n");
        $this->info("✅ View created successfully: {$fullPath}");
        
        return 0;
    }
}