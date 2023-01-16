<?php

return [

    'notifications' => [

        'mail' => [
            'enabled'   => env('UPDATE_NOTIFICATIONS_MAIL_ENABLED', false),
            'name'      => env('UPDATE_NOTIFICATIONS_MAIL_NAME', env('MAIL_FROM_NAME')),
            'from'      => env('UPDATE_NOTIFICATIONS_MAIL_FROM', env('MAIL_FROM_ADDRESS')),
            'to'        => env('UPDATE_NOTIFICATIONS_MAIL_TO', 'admin@mydomain.com'),
        ],

        'slack' => [
            'enabled'   => env('UPDATE_NOTIFICATIONS_SLACK_ENABLED', false),
            'emoji'     => env('UPDATE_NOTIFICATIONS_SLACK_EMOJI', ':warning:'),
            'from'      => env('UPDATE_NOTIFICATIONS_SLACK_FROM', 'Akaunting Update'),
            'to'        => env('UPDATE_NOTIFICATIONS_SLACK_TO'), // webhook url
            'channel'   => env('UPDATE_NOTIFICATIONS_SLACK_CHANNEL', null), // set null to use the default channel of webhook
        ],

    ],

];
