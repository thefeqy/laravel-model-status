<?php

namespace Thefeqy\ModelStatus\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallModelStatusCommand extends Command
{
    protected $signature = 'model-status:install';
    protected $description = 'Prepares the Laravel Model Status package for use in Laravel.';

    private const REPO_URL = 'https://github.com/thefeqy/laravel-model-status';

    public function handle(): void
    {
        // Check if the package is already installed
        if ($this->isAlreadyInstalled()) {
            $this->warn('⚠️ Laravel Model Status is already installed. No changes were made.');
            return;
        }

        $this->info('🚀 Installing Laravel Model Status...');

        $this->copyConfig();
        $this->addEnvKeys('.env');
        $this->addEnvKeys('.env.example');

        // Mark installation as complete
        $this->markAsInstalled();

        $this->info("\n✅ Installation complete! You can now start using Laravel Model Status.");

        // Ask user to star the repository
        if ($this->askToStarRepository()) {
            $this->openRepositoryInBrowser();
        }
    }

    /**
     * Checks if Laravel Model Status is already installed.
     */
    private function isAlreadyInstalled(): bool
    {
        return File::exists(storage_path('model_status_installed.lock'));
    }

    /**
     * Marks the package as installed by creating a lock file.
     */
    private function markAsInstalled(): void
    {
        File::put(storage_path('model_status_installed.lock'), now()->toDateTimeString());
    }

    /**
     * Publishes the config file if it doesn't already exist.
     */
    private function copyConfig(): void
    {
        if (file_exists(config_path('model-status.php'))) {
            $this->warn('⚠️ Config file already exists: config/model-status.php');
            return;
        }

        $this->callSilent('vendor:publish', [
            '--tag' => 'model-status-config',
        ]);

        $this->info('✅ Config file published: config/model-status.php');
    }

    /**
     * Adds missing environment variables to the given env file with comments.
     */
    private function addEnvKeys(string $envFile): void
    {
        $filePath = base_path($envFile);

        if (! file_exists($filePath)) {
            $this->warn("⚠️ Skipping: {$envFile} not found.");
            return;
        }

        $fileContent = file_get_contents($filePath);

        // Environment variables related to Model Status
        $envSection = <<<EOL

# --------------------------------------------------------------------------
# 📌 Laravel Model Status Configuration
# These variables define the behavior of the package.
# --------------------------------------------------------------------------

MODEL_STATUS_COLUMN=status
MODEL_STATUS_COLUMN_LENGTH=10
MODEL_STATUS_ACTIVE=active
MODEL_STATUS_INACTIVE=inactive

EOL;

        // Check if any of the variables already exist
        if (str_contains($fileContent, 'MODEL_STATUS_COLUMN')) {
            $this->info("✅ {$envFile} is already up to date.");
            return;
        }

        // Append the section to the .env file
        file_put_contents($filePath, PHP_EOL . $envSection . PHP_EOL, FILE_APPEND);

        $this->info("✅ Added Laravel Model Status environment variables to {$envFile}");
    }

    /**
     * Asks the user if they want to star the GitHub repository.
     */
    private function askToStarRepository(): bool
    {
        if (! $this->input->isInteractive()) {
            return false;
        }

        return $this->confirm('⭐ Want to support Laravel Model Status by starring it on GitHub?', false);
    }

    /**
     * Opens the repository in the user's default browser.
     */
    private function openRepositoryInBrowser(): void
    {
        $this->info('Opening GitHub repository... 🌍');

        if (PHP_OS_FAMILY === 'Darwin') {
            exec('open ' . self::REPO_URL);
        } elseif (PHP_OS_FAMILY === 'Windows') {
            exec('start ' . self::REPO_URL);
        } elseif (PHP_OS_FAMILY === 'Linux') {
            exec('xdg-open ' . self::REPO_URL);
        }
    }
}
