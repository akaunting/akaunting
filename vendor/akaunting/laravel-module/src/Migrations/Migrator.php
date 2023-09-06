<?php

namespace Akaunting\Module\Migrations;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Akaunting\Module\Module;
use Akaunting\Module\Support\Config\GenerateConfigReader;

class Migrator
{
    /**
     * Module instance.
     *
     * @var \Akaunting\Module\Module
     */
    protected $module;

    /**
     * Laravel Application instance.
     *
     * @var Application.
     */
    protected $laravel;

    /**
     * The database connection to be used
     *
     * @var string
     */
    protected $database = '';

    /**
     * Create new instance.
     *
     * @param Module $module
     * @param Application $application
     */
    public function __construct(Module $module, Application $application)
    {
        $this->module = $module;
        $this->laravel = $application;
    }

    /**
     * Set the database connection to be used
     *
     * @param $database
     *
     * @return $this
     */
    public function setDatabase($database)
    {
        if (is_string($database) && $database) {
            $this->database = $database;
        }

        return $this;
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Get migration path.
     *
     * @return string
     */
    public function getPath()
    {
        $config = $this->module->get('migration');

        $migrationPath = GenerateConfigReader::read('migration');
        $path = (is_array($config) && array_key_exists('path', $config)) ? $config['path'] : $migrationPath->getPath();

        return $this->module->getExtraPath($path);
    }

    /**
     * Get migration files.
     *
     * @param boolean $reverse
     * @return array
     */
    public function getMigrations($reverse = false)
    {
        $files = $this->laravel['files']->glob($this->getPath() . '/*_*.php');

        // Once we have the array of files in the directory we will just remove the
        // extension and take the basename of the file which is all we need when
        // finding the migrations that haven't been run against the databases.
        if ($files === false) {
            return [];
        }

        $files = array_map(function ($file) {
            return str_replace('.php', '', basename($file));
        }, $files);

        // Once we have all of the formatted file names we will sort them and since
        // they all start with a timestamp this should give us the migrations in
        // the order they were actually created by the application developers.
        sort($files);

        if ($reverse) {
            return array_reverse($files);
        }

        return $files;
    }

    /**
     * Rollback migration.
     *
     * @return array
     */
    public function rollback()
    {
        $migrations = $this->getLast($this->getMigrations(true));

        $this->requireFiles($migrations->toArray());

        $migrated = [];

        foreach ($migrations as $migration) {
            $data = $this->find($migration);

            if ($data->count()) {
                $migrated[] = $migration;

                $this->down($migration);

                $data->delete();
            }
        }

        return $migrated;
    }

    /**
     * Reset migration.
     *
     * @return array
     */
    public function reset()
    {
        $migrations = $this->getMigrations(true);

        $this->requireFiles($migrations);

        $migrated = [];

        foreach ($migrations as $migration) {
            $data = $this->find($migration);

            if ($data->count()) {
                $migrated[] = $migration;

                $this->down($migration);

                $data->delete();
            }
        }

        return $migrated;
    }

    /**
     * Run down schema from the given migration name.
     *
     * @param string $migration
     */
    public function down($migration)
    {
        $this->resolve($migration)->down();
    }

    /**
     * Run up schema from the given migration name.
     *
     * @param string $migration
     */
    public function up($migration)
    {
        $this->resolve($migration)->up();
    }

    /**
     * Resolve a migration instance from a file.
     *
     * @param string $file
     *
     * @return object
     */
    public function resolve($file)
    {
        $file = implode('_', array_slice(explode('_', $file), 4));

        $class = Str::studly($file);

        return new $class();
    }

    /**
     * Require in all the migration files in a given path.
     *
     * @param array  $files
     */
    public function requireFiles(array $files)
    {
        $path = $this->getPath();
        foreach ($files as $file) {
            $this->laravel['files']->requireOnce($path . '/' . $file . '.php');
        }
    }

    /**
     * Get table instance.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function table()
    {
        return $this->laravel['db']->connection($this->database ?: null)->table(config('database.migrations'));
    }

    /**
     * Find migration data from database by given migration name.
     *
     * @param string $migration
     *
     * @return object
     */
    public function find($migration)
    {
        return $this->table()->whereMigration($migration);
    }

    /**
     * Save new migration to database.
     *
     * @param string $migration
     *
     * @return mixed
     */
    public function log($migration)
    {
        return $this->table()->insert([
            'migration' => $migration,
            'batch' => $this->getNextBatchNumber(),
        ]);
    }

    /**
     * Get the next migration batch number.
     *
     * @return int
     */
    public function getNextBatchNumber()
    {
        return $this->getLastBatchNumber() + 1;
    }

    /**
     * Get the last migration batch number.
     *
     * @param array|null $migrations
     * @return int
     */
    public function getLastBatchNumber($migrations = null)
    {
        $table = $this->table();

        if (is_array($migrations)) {
            $table = $table->whereIn('migration', $migrations);
        }

        return $table->max('batch');
    }

    /**
     * Get the last migration batch.
     *
     * @param array $migrations
     *
     * @return Collection
     */
    public function getLast($migrations)
    {
        $query = $this->table()
            ->where('batch', $this->getLastBatchNumber($migrations))
            ->whereIn('migration', $migrations);

        $result = $query->orderBy('migration', 'desc')->get();

        return collect($result)->map(function ($item) {
            return (array) $item;
        })->pluck('migration');
    }

    /**
     * Get the ran migrations.
     *
     * @return Collection
     */
    public function getRan()
    {
        return $this->table()->pluck('migration');
    }
}
