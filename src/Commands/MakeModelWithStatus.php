<?php

namespace Thefeqy\ModelStatus\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class MakeModelWithStatus extends Command
{
    protected $signature = 'make:model-status {name : The name of the model}';
    protected $description = 'Create a model and migration with a configurable status column, and add the HasActiveScope trait by default.';

    public function handle(): void
    {
        $name = $this->argument('name');
        $tableName = Str::plural(Str::snake($name));

        // Step 1: Create the model and migration
        Artisan::call("make:model $name -m");
        $this->info("Model $name created successfully!");

        // Step 2: Update the model to include the HasActiveScope trait
        $this->addTraitToModel($name);

        // Step 3: Add the status column to the migration
        $migrationFile = $this->getLatestMigration($tableName);
        if ($migrationFile) {
            $this->addStatusColumnToMigration($migrationFile);
            $this->info("Added status column to migration: " . basename($migrationFile));
        } else {
            $this->error("Migration for $name not found.");
        }
    }

    /**
     * Get the latest migration file for the table.
     */
    protected function getLatestMigration(string $tableName): ?string
    {
        $migrationPath = database_path('migrations');
        return collect(scandir($migrationPath))
            ->filter(fn($file) => str_contains($file, "create_{$tableName}_table"))
            ->map(fn($file) => $migrationPath . '/' . $file)
            ->last();
    }

    /**
     * Add the status column to the migration file.
     */
    protected function addStatusColumnToMigration(string $filePath): void
    {
        $columnName = Config::get('model-status.column_name', 'status');
        $defaultValue = Config::get('model-status.default_value', 'active');
        $columnLength = Config::get('model-status.column_length', 10);

        $content = file_get_contents($filePath);
        $updatedContent = str_replace(
            "\$table->timestamps();",
            "\$table->string('$columnName', $columnLength)->default('$defaultValue');\n            \$table->timestamps();",
            $content
        );
        file_put_contents($filePath, $updatedContent);
    }

    /**
     * Add the HasActiveScope trait to the model.
     */
    protected function addTraitToModel(string $modelName): void
    {
        $modelPath = app_path("Models/$modelName.php");
        if (!file_exists($modelPath)) {
            $this->error("Model $modelName not found at $modelPath.");
            return;
        }

        $content = file_get_contents($modelPath);

        // Add the use statement for the HasActiveScope trait
        $useTrait = "use Thefeqy\\ModelStatus\\Traits\\HasActiveScope;";
        if (!str_contains($content, $useTrait)) {
            // Add the use statement after the namespace declaration
            $content = preg_replace(
                '/namespace App\\\Models;/', // Find the namespace declaration
                "namespace App\\Models;\n\n$useTrait", // Add the use statement
                $content
            );
        }

        // Add the trait to the class
        if (!str_contains($content, 'use HasActiveScope;')) {
            $content = preg_replace(
                '/class ' . $modelName . ' extends Model[^{]*{/', // Match the class definition
                "class $modelName extends Model\n{\n    use HasActiveScope;\n", // Add the trait within the class
                $content
            );
        }

        file_put_contents($modelPath, $content);
        $this->info("Added HasActiveScope trait to $modelName.");
    }
}
