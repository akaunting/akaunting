<?php

namespace Laratrust\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laratrust:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup migration and models for Laratrust';

    /**
     * Commands to call with their description.
     *
     * @var array
     */
    protected $calls = [
        'laratrust:migration' => 'Creating migration',
        'laratrust:role' => 'Creating Role model',
        'laratrust:permission' => 'Creating Permission model',
        'laratrust:add-trait' => 'Adding LaratrustUserTrait to User model'
    ];

    /**
     * Create a new command instance
     *
     * @return void
     */
    public function __construct()
    {
        if (Config::get('laratrust.teams.enabled')) {
            $this->calls['laratrust:team'] = 'Creating Team model';
        }

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->calls as $command => $info) {
            $this->line(PHP_EOL . $info);
            $this->call($command);
        }
    }
}
