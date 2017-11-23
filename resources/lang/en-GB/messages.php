<?php

return [

    'success' => [
        'added'             => ':type added!',
        'updated'           => ':type updated!',
        'deleted'           => ':type deleted!',
    ],
    'error' => [
        'not_user_company'  => 'Error: You are not allowed to manage this company!',
        'customer'          => 'Error: You can not created customer! :name use this email address.',
    ],
    'warning' => [
        'deleted'           => 'Warning: You are not allowed to delete <b>:name</b> because it has :text related.',
        'disabled'          => 'Warning: You are not allowed to disable <b>:name</b> because it has :text related.',
    ],

];
