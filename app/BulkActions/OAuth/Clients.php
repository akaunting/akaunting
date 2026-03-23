<?php

namespace App\BulkActions\OAuth;

use App\Abstracts\BulkAction;
use App\Jobs\OAuth\DeleteClient;
use App\Models\OAuth\Client;

class Clients extends BulkAction
{
    public $model = Client::class;

    public $text = 'oauth.clients';

    public $path = [
        'group' => 'oauth',
        'type'  => 'clients',
    ];

    public $actions = [
        'delete' => [
            'icon'       => 'delete',
            'name'       => 'general.delete',
            'message'    => 'bulk_actions.message.delete',
            'permission' => 'delete-oauth-clients',
        ],
    ];

    public function destroy($request)
    {
        $clients = $this->getSelectedRecords($request);

        foreach ($clients as $client) {
            try {
                $this->dispatch(new DeleteClient($client));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
