<?php

use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Setting\Category;

return [

    // Group
    'banking' => [

        // Type
        'transactions' => [

            //'path' => 'banking/transactions',
            //'title_type  => 'transactions',
            //'sample_file' => 'public/files/import/transactions.xlsx',
            /*'form_params' => [
                'id' => 'import',
                '@submit.prevent' => 'onSubmit',
                '@keydown' => 'form.errors.clear($event.target.name)',
                'files' => true,
                'role' => 'form',
                'class' => 'form-loading-button',
                'novalidate' => true,
                'route' => '',
                'url' => '',
            ],*/
            'document_link' => 'https://akaunting.com/hc/docs/import-export/importing-transactions/',
            'view' => 'banking.transactions.import',

        ],
    ],

];
