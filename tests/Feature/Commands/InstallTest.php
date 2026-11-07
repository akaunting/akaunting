<?php

namespace Tests\Feature\Commands;

use App\Utilities\Installer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Tests\Feature\FeatureTestCase;

class InstallTest extends FeatureTestCase
{
    public function testItShouldPassTheDriverCheckWhenTheDriverIsLoaded()
    {
        $this->assertNull(Installer::checkDbDriver());
    }

    public function testItShouldNameTheMissingDriver()
    {
        // The open connection stays as it is, only new ones read the config
        Config::set('database.connections.sqlite.driver', 'missing');

        $error = trans('install.requirements.extension', ['extension' => 'pdo_missing']);

        $this->assertSame($error, Installer::checkDbDriver());
        $this->assertContains($error, Installer::checkServerRequirements());
    }

    public function testItShouldStopBeforeTheDatabaseStepWhenTheDriverIsMissing()
    {
        Config::set('database.connections.sqlite.driver', 'missing');

        $this->artisan('install', [
            '--db-host' => '127.0.0.1',
            '--db-port' => '5432',
            '--db-name' => 'akaunting',
            '--db-username' => 'akaunting',
            '--db-password' => 'secret',
            '--db-prefix' => 'ak_',
            '--company-name' => 'My Company',
            '--company-email' => 'my@company.com',
            '--admin-email' => 'admin@company.com',
            '--admin-password' => 'secret',
            '--locale' => 'en-GB',
            '--no-interaction' => true,
        ])
            ->expectsOutput('Error: ' . trans('install.requirements.extension', ['extension' => 'pdo_missing']))
            ->doesntExpectOutput('Creating database tables')
            ->assertExitCode(1);
    }

    public function testItShouldLogWhyTheConnectionFailed()
    {
        $database = storage_path('framework/testing/missing.sqlite');

        Log::shouldReceive('error')
            ->once()
            ->withArgs(fn ($message) => str_contains($message, 'Installer could not connect') && str_contains($message, $database));

        $this->assertFalse(Installer::isDbValid('', '', $database, '', ''));
    }
}
