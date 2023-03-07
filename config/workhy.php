<?php

return [
    'panel_url' => env('WORKHY_PANEL_URL'),
    'auth' => [
        'signed_key_name' => 'signed_token',
        'signed_token_secret' => env('WORKHY_PANEL_SIGNED_TOKEN_SECRET'),
        'signed_token_cipher' => env('WORKHY_PANEL_SIGNED_TOKEN_CIPHER', 'AES-256-CBC'),
        'guards' => [],
    ]
];
