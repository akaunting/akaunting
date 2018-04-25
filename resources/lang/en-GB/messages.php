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
        'over_payment'      => 'Error: Payment not added! Amount passes the total.',
        'not_user_company'  => 'Error: You are not allowed to manage this company!',
        'customer'          => 'Error: User not created! :name already uses this email address.',
        'no_file'           => 'Error: No file selected!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
    ],
    'warning' => [
        'deleted'           => 'Warning: You are not allowed to delete <b>:name</b> because it has :text related.',
        'disabled'          => 'Warning: You are not allowed to disable <b>:name</b> because it has :text related.',
    ],

];
