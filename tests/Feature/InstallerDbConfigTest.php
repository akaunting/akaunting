<?php

namespace Tests\Feature;

use App\Utilities\Installer;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class InstallerDbConfigTest extends TestCase
{
    public function test_save_db_variables_sets_port_and_read_write_hosts()
    {
        // Setup: ensure we have a mysql connection config with read/write hosts
        Config::set('database.default', 'mysql');
        Config::set('database.connections.mysql', [
            'driver' => 'mysql',
            'read' => ['host' => ['127.0.0.1']],
            'write' => ['host' => ['127.0.0.1']],
            'port' => '3306',
            'host' => '127.0.0.1',
            'database' => 'forge',
            'username' => 'forge',
            'password' => '',
            'prefix' => 'ak_',
        ]);

        // Create a dummy .env to avoid file errors
        $envPath = base_path('.env');
        $envBackup = null;
        if (is_file($envPath)) {
            $envBackup = file_get_contents($envPath);
        } else {
            file_put_contents($envPath, "APP_KEY=base64:test\nDB_HOST=127.0.0.1\nDB_PORT=3306\n");
        }

        Installer::saveDbVariables('192.168.1.50', '3307', 'akaunting', 'root', 'pass', 'ak_');

        $db = Config::get('database.connections.mysql');

        // Restore .env
        if ($envBackup !== null) {
            file_put_contents($envPath, $envBackup);
        } else {
            @unlink($envPath);
        }

        $this->assertEquals('192.168.1.50', $db['host'], 'host should be updated');
        $this->assertEquals('3307', $db['port'], 'port should be updated to custom port 3307');
        $this->assertEquals('192.168.1.50', $db['read']['host'][0], 'read host should be updated for Docker/non-default host');
        $this->assertEquals('192.168.1.50', $db['write']['host'][0], 'write host should be updated');
        $this->assertEquals('akaunting', $db['database']);
    }
}
