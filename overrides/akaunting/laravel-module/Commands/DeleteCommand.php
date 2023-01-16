<?php

namespace Akaunting\Module\Commands;

use App\Console\Commands\UninstallModule;

class DeleteCommand extends UninstallModule
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:delete {alias} {company} {locale=en-GB}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the specified module.';
}
