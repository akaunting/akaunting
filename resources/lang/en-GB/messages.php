<?php

return [

    'success' => [
        'added'             => ':type added!',
        'updated'           => ':type updated!',
        'deleted'           => ':type deleted!',
        'duplicated'        => ':type duplicated!',
        'imported'          => ':type imported!',
        'import_queued'     => ':type import has been scheduled! You will receive an email when it is finished.',
        'exported'          => ':type exported!',
        'export_queued'     => ':type export of the current page has been scheduled! You will receive an email when it is ready to download.',
        'enabled'           => ':type enabled!',
        'disabled'          => ':type disabled!',

        'clear_all'         => 'Great! You\'ve cleared all of your :type.',
    ],

    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'Error: You are not allowed to manage this company!',
        'customer'          => 'Error: User not created! :name already uses this email address.',
        'no_file'           => 'Error: No file selected!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'change_type'       => 'Error: Can not change the type because it has :text related!',
        'invalid_apikey'    => 'Error: The API Key entered is invalid!',
        'import_column'     => 'Error: :message Column name: :column. Line number: :line.',
        'import_sheet'      => 'Error: Sheet name is not valid. Please, check the sample file.',
    ],

    'warning' => [
        'deleted'           => 'Warning: You are not allowed to delete <b>:name</b> because it has :text related.',
        'disabled'          => 'Warning: You are not allowed to disable <b>:name</b> because it has :text related.',
        'reconciled_tran'   => 'Warning: You are not allowed to change/delete transaction because it is reconciled!',
        'reconciled_doc'    => 'Warning: You are not allowed to change/delete :type because it has reconciled transactions!',
        'disable_code'      => 'Warning: You are not allowed to disable or change the currency of <b>:name</b> because it has :text related.',
        'payment_cancel'    => 'Warning: You have cancelled your recent :method payment!',
    ],

];
