<?php

namespace Laratrust\Console;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Console\Seeds\SeederMakeCommand as LaravelMakeSeederCommand;

class MakeSeederCommand extends LaravelMakeSeederCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laratrust:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the seeder following the Laratrust specifications.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (file_exists($this->seederPath())) {
            $this->line('');

            $this->warn("The LaratrustSeeder file already exists. Delete the existing one if you want to create a new one.");
            $this->line('');
            return;
        }

        try {
            $seederClass = $this->createSeederClass();
            $this->files->put($this->seederPath(), $seederClass);
            $this->info("Seeder successfully created!");
        } catch (Exception $exception) {
            $folder = $this->files->dirname($this->seederPath());
            $where = substr($folder, strpos($folder, 'database'));

            $this->error(
                "Couldn't create seeder.\n".
                "Check the write permissions within the $where directory."
            );
        }

        $this->line('');
    }

    /**
     * Create the seeder
     *
     * @return string
     */
    protected function createSeederClass(): string
    {
        $stub = $this->files->get($this->getStub());

        $this->replaceSeederNamespace($stub);
        $this->replaceModelClassNames($stub);
        $this->replaceTableNames($stub);

        return $stub;
    }

    /**
     * Replace the namespace of the seeder.
     * @param  string  $stub
     */
    protected function replaceSeederNamespace(string & $stub)
    {
        $namespace = '';

        if (version_compare($this->getLaravel()->version(), '8.0') >= 0) {
            $namespace = "\nnamespace Database\Seeders;\n";
        }

        $this->replaceStubParameter($stub, 'namespace', $namespace);
    }

    /**
     * Replace the models class names in the stub.
     * @param  string  $stub
     */
    protected function replaceModelClassNames(string & $stub)
    {
        $role = Config::get('laratrust.models.role', 'App\Role');
        $this->replaceStubParameter($stub, 'roleConfiguredModelClass', '\\' . ltrim($role, '\\'));

        $permission = Config::get('laratrust.models.permission', 'App\Permission');
        $this->replaceStubParameter($stub, 'permissionConfiguredModelClass', '\\' . ltrim($permission, '\\'));
   
        $user = new Collection(Config::get('laratrust.user_models', ['App\User']));
        $user = $user->first();
        $this->replaceStubParameter($stub, 'userConfiguredModelClass', '\\' . ltrim($user, '\\'));
    }

    /**
     * Replace the table names in the stub.
     * @param  string  $stub
     */
    protected function replaceTableNames(string & $stub)
    {
        $rolePermission = Config::get('laratrust.tables.permission_role');
        $this->replaceStubParameter($stub, 'permission_roleConfiguredTableName', $rolePermission);

        $permissionUser = Config::get('laratrust.tables.permission_user');
        $this->replaceStubParameter($stub, 'permission_userConfiguredTableName', $permissionUser);

        $roleUser = Config::get('laratrust.tables.role_user');
        $this->replaceStubParameter($stub, 'role_userConfiguredTableName', $roleUser);

        $rolesTable = Config::get('laratrust.tables.roles');
        $this->replaceStubParameter($stub, 'rolesTableName', $rolesTable);

        $permissionsTable = Config::get('laratrust.tables.permissions');
        $this->replaceStubParameter($stub, 'permissionsTableName', $permissionsTable);
    }

    /**
     * Replace a placeholder parameter in the stub.
     * @param  string  $stub
     * @param  string  $parameter
     * @param  string  $value
     */
    protected function replaceStubParameter(string & $stub, string $parameter, string $value)
    {
        $stub = str_replace('{{'.$parameter.'}}', $value, $stub);
    }

    /**
     * Get the seeder stub file.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/seeder.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return __DIR__."/../../$stub";
    }

    /**
     * Get the seeder path.
     *
     * @return string
     */
    protected function seederPath()
    {
        return $this->getPath('LaratrustSeeder');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }
}
