<?php

return [

    'whoops'              => 'Whoops!',
    'hello'               => 'Hello!',
    'salutation'          => 'Regards,<br> :company_name',
    'subcopy'             => 'If you’re having trouble clicking the ":text" button, copy and paste the URL below into your web browser: [:url](:url)',
    'reads'               => 'Read|Reads',
    'read_all'            => 'Read All',
    'mark_read'           => 'Mark Read',
    'mark_read_all'       => 'Mark Read All',
    'new_apps'            => 'New App|New Apps',
    'upcoming_bills'      => 'Upcoming Bills',
    'recurring_invoices'  => 'Recurring Invoices',
    'recurring_bills'     => 'Recurring Bills',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Update failed on :domain',
            'message' => 'The update of :alias from :current_version to :new_version failed in <strong>:step</strong> step with the following message: :error_message',

        ],

        'slack' => [

            'message' => 'Update failed on :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'Import completed',
            'description'       => 'The import has been completed and the records are available in your panel.',

        ],

        'failed' => [

            'subject'           => 'Import failed',
            'description'       => 'Not able to import the file due to the following issues:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Export is ready',
            'description'       => 'The export file is ready to download from the following link:',

        ],

        'failed' => [

            'subject'           => 'Export failed',
            'description'       => 'Not able to create the export file due to the following issue:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type is read this notification!',
        'mark_read_all'         => ':type is read all notifications!',
        'new_app'               => ':type app published.',
        'export'                => 'Your <b>:type</b> export file is ready to <a href=":url" target="_blank"><b>download</b></a>.',
        'import'                => 'Your <b>:type</b> lined <b>:count</b> data is imported successfully.',

    ],
];
