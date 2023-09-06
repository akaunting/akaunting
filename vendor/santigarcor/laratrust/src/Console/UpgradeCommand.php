<?php

namespace Laratrust\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class UpgradeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laratrust:upgrade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration to upgrade laratrust from version 4.0 to 5.0.';

    /**
     * Suffix of the migration name.
     *
     * @var string
     */
    protected $migrationSuffix = 'laratrust_upgrade_tables';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info("There is nothing to upgrade through the command.");
        return;

        $this->line('');
        $this->info("The Laratrust upgrade migration will be created in the database/migration directory");

        $existingMigrations = $this->alreadyExistingMigrations();

        if ($existingMigrations) {
            $this->line('');

            $this->warn($this->getExistingMigrationsWarning($existingMigrations));
        }

        $this->line('');

        if (! $this->confirm("Proceed with the migration creation?", "yes")) {
            return;
        }

        $this->line('');

        $this->info("Creating migration...");

        if ($this->createMigration()) {
            $this->info("Migration successfully created!");
        } else {
            $this->error(
                "Couldn't create migration.\n".
                "Check the write permissions within the database/migrations directory."
            );
        }

        $this->line('');
    }

    /**
     * Create the migration.
     *
     * @return bool
     */
    protected function createMigration()
    {
        $migrationPath = $this->getMigrationPath();

        $this->call('view:clear');
        $output = $this->laravel->view
            ->make('laratrust::upgrade-migration')
            ->with(['laratrust' => Config::get('laratrust')])
            ->render();

        if (!file_exists($migrationPath) && $fs = fopen($migrationPath, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }

    /**
     * Build a warning regarding possible duplication
     * due to already existing migrations.
     *
     * @param  array $existingMigrations
     * @return string
     */
    protected function getExistingMigrationsWarning(array $existingMigrations)
    {
        if (count($existingMigrations) > 1) {
            $base = "Laratrust upgrade migrations already exist.\nFollowing files were found: ";
        } else {
            $base = "Laratrust upgrade migration already exists.\nFollowing file was found: ";
        }

        return $base . array_reduce($existingMigrations, function ($carry, $fileName) {
            return $carry . "\n - " . $fileName;
        });
    }

    /**
     * Check if there is another migration
     * with the same suffix.
     *
     * @return array
     */
    protected function alreadyExistingMigrations()
    {
        $matchingFiles = glob($this->getMigrationPath('*'));

        return array_map(function ($path) {
            return basename($path);
        }, $matchingFiles);
    }

    /**
     * Get the migration path.
     *
     * The date parameter is optional for ability
     * to provide a custom value or a wildcard.
     *
     * @param  string|null $date
     * @return string
     */
    protected function getMigrationPath($date = null)
    {
        $date = $date ?: date('Y_m_d_His');

        return database_path("migrations/${date}_{$this->migrationSuffix}.php");
    }
}
