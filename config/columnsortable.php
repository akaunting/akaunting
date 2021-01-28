<?php

return [

    /*
    spec columns
    */
    'columns' => [
        'alpha' => [
            'rows' => ['name', 'contact_name', 'customer_name', 'vendor_name', 'display_name', 'company_name', 'domain', 'email', 'description', 'code', 'type', 'status', 'vendor', 'account', 'category'],
            'class' => 'fas fa-sort-alpha',
        ],
        'amount' => [
            'rows' => ['amount', 'price', 'sale_price', 'purchase_price', 'total_price', 'current_balance', 'total_price', 'opening_balance'],
            'class' => 'fas fa-sort-amount',
        ],
        'numeric' => [
            'rows' => ['created_at', 'updated_at', 'paid_at', 'issued_at', 'due_at', 'id', 'quantity', 'rate',  'number', 'document_number'],
            'class' => 'fas fa-sort-numeric',
        ],
    ],

    /*
    whether icons should be enabled
     */
    'enable_icons' => true,

    /*
    defines icon set to use when sorted data is none above (alpha nor amount nor numeric)
     */
    'default_icon_set' => 'fas fa-arrow-down sort-icon',

    /*
    icon that shows when generating sortable link while column is not sorted
     */
    'sortable_icon' => 'fas fa-arrow-down sort-icon',

    /*
    generated icon is clickable non-clickable (default)
     */
    'clickable_icon' => false,

    /*
    icon and text separator (any string)
    in case of 'clickable_icon' => true; separator creates possibility to style icon and anchor-text properly
     */
    'icon_text_separator' => '&nbsp; ',

    /*
    suffix class that is appended when ascending order is applied
     */
    'asc_suffix' => '-down',

    /*
    suffix class that is appended when descending order is applied
     */
    'desc_suffix' => '-up',

    /*
    default anchor class, if value is null none is added
     */
    'anchor_class' => null,

    /*
    relation - column separator ex: detail.phone_number means relation "detail" and column "phone_number"
     */
    'uri_relation_column_separator' => '.',

    /*
    formatting function applied to name of column, use null to turn formatting off
     */
    'formatting_function' => 'ucfirst',

    /*
    inject title parameter in query strings, use null to turn injection off
    example: 'inject_title' => 't' will result in ..user/?t="formatted title of sorted column"
     */
    'inject_title_as' => null,

    /*
    allow request modification, when default sorting is set but is not in URI (first load)
     */
    'allow_request_modification' => true,

    /*
    default order for: $user->sortable('id') usage
     */
    'default_direction' => 'asc',

    /*
    default order for non-sorted columns
     */
    'default_direction_unsorted' => 'asc',

    /*
    use the first defined sortable column (Model::$sortable) as default
    also applies if sorting parameters are invalid for example: 'sort' => 'name', 'direction' => ''
     */
    'default_first_column' => false,

    /*
    join type: join vs leftJoin (default leftJoin)
    for more information see https://github.com/Kyslik/column-sortable/issues/59
    */
    'join_type' => 'leftJoin',
];
