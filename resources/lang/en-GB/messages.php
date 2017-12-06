<?php

return [

    'success' => [
        'added'             => ':type added!',
        'updated'           => ':type updated!',
        'deleted'           => ':type deleted!',
        'duplicated'        => ':type duplicated!',
        'imported'          => ':type imported!',
    ],
    'error' => [
        'payment_add'       => 'Error: You can not add payment! You should check add amount.',
        'not_user_company'  => 'Error: You are not allowed to manage this company!',
        'customer'          => 'Error: You can not created user! :name use this email address.',
        'no_file'           => 'Error: No file selected!',
    ],
    'warning' => [
        'deleted'           => 'Warning: You are not allowed to delete <b>:name</b> because it has :text related.',
        'disabled'          => 'Warning: You are not allowed to disable <b>:name</b> because it has :text related.',
    ],

];
