<?php

namespace Database\Seeds;

use App\Models\OAuth\Scope;
use Illuminate\Database\Seeder;

class OAuthScopes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates the default OAuth scopes required for MCP and API integrations.
     * Existing scopes are skipped (firstOrCreate) so re-running is safe.
     *
     * @return void
     */
    public function run()
    {
        $scopes = [
            [
                'key'         => 'mcp:use',
                'name'        => 'MCP Access',
                'description' => 'Access MCP server capabilities and interact with your data via Model Context Protocol',
                'group'       => 'mcp',
                'enabled'     => true,
                'is_default'  => false,
                'sort_order'  => 10,
                'created_from' => 'core.seed',
            ],
            [
                'key'         => 'read',
                'name'        => 'Read Access',
                'description' => 'Read your account data',
                'group'       => 'basic',
                'enabled'     => true,
                'is_default'  => true,
                'sort_order'  => 20,
                'created_from' => 'core.seed',
            ],
            [
                'key'         => 'write',
                'name'        => 'Write Access',
                'description' => 'Create and modify your account data',
                'group'       => 'basic',
                'enabled'     => true,
                'is_default'  => false,
                'sort_order'  => 30,
                'created_from' => 'core.seed',
            ],
            [
                'key'         => 'admin',
                'name'        => 'Admin Access',
                'description' => 'Full administrative access to your account',
                'group'       => 'advanced',
                'enabled'     => true,
                'is_default'  => false,
                'sort_order'  => 40,
                'created_from' => 'core.seed',
            ],
        ];

        foreach ($scopes as $data) {
            Scope::firstOrCreate(['key' => $data['key']], $data);
        }
    }
}
