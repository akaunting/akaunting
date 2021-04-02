<?php

return [

    'whoops'              => 'Whoops!',
    'hello'               => 'Hello!',
    'salutation'          => 'Regards,<br> :company_name',
    'subcopy'             => 'If you’re having trouble clicking the ":text" button, copy and paste the URL below into your web browser: [:url](:url)',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Update failed on :domain',
            'message' => 'The update of :alias from :current_version to :new_version failed in <strong>:step</strong> step with the following message: :error_message',

        ],

        'slack' => [

            'message' => 'Update failed on :domain',

        ],

    ],

];
