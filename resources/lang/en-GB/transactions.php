<?php

return [

    'payment_received'      => 'Payment Received',
    'payment_made'          => 'Payment Made',
    'paid_by'               => 'Paid By',
    'paid_to'               => 'Paid To',
    'related_invoice'       => 'Related Invoice',
    'related_bill'          => 'Related Bill',
    'recurring_income'      => 'Recurring Income',
    'recurring_expense'     => 'Recurring Expense',

    'form_description' => [
        'general'           => 'Here you can enter the general information of transaction such as date, amount, account, description, etc.',
        'assign_income'     => 'Select a category and customer to make your reports more detailed.',
        'assign_expense'    => 'Select a category and vendor to make your reports more detailed.',
        'other'             => 'Enter a number and reference to keep the transaction linked to your records.',
    ],

    'slider' => [
        'create'            => ':user created this transaction on :date',
        'attachments'       => 'Download the files attached to this transaction',
        'create_recurring'  => ':user created this recurring template on :date',
        'schedule'          => 'Repeat every :interval :frequency since :date',
        'children'          => ':count transactions were created automatically',
        'transfer_headline' => '<div> <span class="font-bold"> From: </span> :from_account </div> <div> <span class="font-bold"> to: </span> :to_account </div>',
        'transfer_desc'     => 'Transfer created on :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Your customer can view the transaction at this link',
            'copy_link'     => 'Copy the link and share it with your customer.',
        ],

        'expense' => [
            'show_link'     => 'Your vendor can view the transaction at this link',
            'copy_link'     => 'Copy the link and share it with your vendor.',
        ],
    ],

    'sticky' => [
        'description'       => 'You are previewing how your customer will see the web version of your payment.',
    ],

];
