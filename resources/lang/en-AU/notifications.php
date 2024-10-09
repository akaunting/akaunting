<?php

return [

    'whoops'              => 'Whoops!',
    'hello'               => 'Hello!',
    'salutation'          => 'Regards,<br> :company_name',
    'subcopy'             => 'If you’re having trouble clicking the ":text" button, copy and paste the URL below into your web browser: [:url](:url)',
    'mark_read'           => 'Mark Read',
    'mark_read_all'       => 'Mark Read All',
    'empty'               => 'Woohoo, notification zero!',
    'new_apps'            => ':app is available. <a href=":url">Check it out now</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Update failed on :domain',
            'description'   => 'The update of :alias from :current_version to :new_version failed in <strong>:step</strong> step with the following message: :error_message',

        ],

        'slack' => [

            'description'   => 'Update failed on :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'Download is ready',
            'description'   => 'The file is ready to download from the following link:',

        ],

        'failed' => [

            'title'         => 'Download failed',
            'description'   => 'Not able to create the file due to the following issue:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Import completed',
            'description'   => 'The import has been completed and the records are available in your panel.',

        ],

        'failed' => [

            'title'         => 'Import failed',
            'description'   => 'Not able to import the file due to the following issues:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Export is ready',
            'description'   => 'The export file is ready to download from the following link:',

        ],

        'failed' => [

            'title'         => 'Export failed',
            'description'   => 'Not able to create the export file due to the following issue:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Invalid :type Email',
            'description'   => 'The :email email address has been reported as invalid, and the person has been disabled. Please check the following error message and fix the email address:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'Download is ready',
            'description'   => 'Your <strong>:type</strong> file is ready to <a href=":url" target="_blank"><strong>download</strong></a>.',

        ],

        'download_failed' => [

            'title'         => 'Download failed',
            'description'   => 'Not able to create the file due to several issues. Check out your email for the details.',

        ],

        'export_completed' => [

            'title'         => 'Export is ready',
            'description'   => 'Your <strong>:type</strong> export file is ready to <a href=":url" target="_blank"><strong>download</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Export failed',
            'description'   => 'Not able to create the export file due to several issues. Check out your email for the details.',

        ],

        'import_completed' => [

            'title'         => 'Import completed',
            'description'   => 'Your <strong>:type</strong> lined <strong>:count</strong> data is imported successfully.',

        ],

        'import_failed' => [

            'title'         => 'Import failed',
            'description'   => 'Not able to import the file due to several issues. Check out your email for the details.',

        ],

        'new_apps' => [

            'title'         => 'New App',
            'description'   => '<strong>:name</strong> app is out. You can <a href=":url">click here</a> to see the details.',

        ],

        'invoice_new_customer' => [

            'title'         => 'New Invoice',
            'description'   => '<strong>:invoice_number</strong> invoice is created. You can <a href=":invoice_portal_link">click here</a> to see the details and proceed with the payment.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Invoice Overdue',
            'description'   => '<strong>:invoice_number</strong> invoice was due <strong>:invoice_due_date</strong>. You can <a href=":invoice_portal_link">click here</a> to see the details and proceed with the payment.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Invoice Overdue',
            'description'   => '<strong>:invoice_number</strong> invoice was due <strong>:invoice_due_date</strong>. You can <a href=":invoice_admin_link">click here</a> to see the details.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'New Recurring Invoice',
            'description'   => '<strong>:invoice_number</strong> invoice is created based on your recurring circle. You can <a href=":invoice_portal_link">click here</a> to see the details and proceed with the payment.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'New Recurring Invoice',
            'description'   => '<strong>:invoice_number</strong> invoice is created based on <strong>:customer_name</strong> recurring circle. You can <a href=":invoice_admin_link">click here</a> to see the details.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Invoice Viewed',
            'description'   => '<strong>:customer_name</strong> has viewed the <strong>:invoice_number</strong> invoice. You can <a href=":invoice_admin_link">click here</a> to see the details.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Payment Received',
            'description'   => 'Thank you for the payment for <strong>:invoice_number</strong> invoice. You can <a href=":invoice_portal_link">click here</a> to see the details.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Payment Received',
            'description'   => 'Thank you for the payment for <strong>:invoice_number</strong> invoice. You can <a href=":invoice_portal_link">click here</a> to see the details.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Payment Received',
            'description'   => ':customer_name recorded payment for <strong>:invoice_number</strong> invoice. You can <a href=":invoice_admin_link">click here</a> to see the details.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Bill Overdue',
            'description'   => '<strong>:bill_number</strong> bill was due <strong>:bill_due_date</strong>. You can <a href=":bill_admin_link">click here</a> to see the details.',

        ],

        'bill_recur_admin' => [

            'title'         => 'New Recurring Bill',
            'description'   => '<strong>:bill_number</strong> bill is created based on <strong>:vendor_name</strong> recurring circle. You can <a href=":bill_admin_link">click here</a> to see the details.',

        ],

        'invalid_email' => [

            'title'         => 'Invalid :type Email',
            'description'   => 'The <strong>:email</strong> email address has been reported as invalid, and the person has been disabled. Please check and fix the email address.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type is read this notification!',
        'mark_read_all'         => ':type is read all notifications!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Firefox Icon Configuration',
            'description'  => '<span class="font-medium">If your icons not appear please;</span> <br /> <span class="font-medium">Please Allow pages to choose their own fonts, instead of your selections above</span> <br /><br /> <span class="font-bold"> Settings (Preferences) > Fonts > Advanced </span>',

        ],

    ],

];
