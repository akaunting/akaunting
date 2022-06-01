<?php

return [

    'types' => [
        'alpha' => [
            'fields'                => ['name', 'contact_name', 'customer_name', 'vendor_name', 'display_name', 'company_name', 'domain', 'email', 'description', 'code', 'type', 'status', 'vendor', 'account', 'category'],
            'icon'                  => 'arrow_drop',
        ],
        'amount' => [
            'fields'                => ['amount', 'price', 'sale_price', 'purchase_price', 'total_price', 'current_balance', 'total_price', 'opening_balance'],
            'icon'                  => 'arrow_drop',
        ],
        'numeric' => [
            'fields'                => ['created_at', 'updated_at', 'paid_at', 'issued_at', 'due_at', 'id', 'quantity', 'rate',  'number', 'document_number'],
            'icon'                  => 'arrow_drop',
        ],
    ],

    'icons' => [
        'enabled'                   => true,

        'wrapper'                   => '<span class="material-icons-outlined text-xl align-middle" style="line-height:0">{icon}</span>',

        'default'                   => 'arrow_drop',

        // Icon that shows when generating sortable link for columns not sorted by, not applied if value is null
        'sortable'                  => null,

        'clickable'                 => false,

        'prefix'                    => '&nbsp;',

        'suffix'                    => '',

        'asc_suffix'                => '_down',

        'desc_suffix'               => '_up',
    ],

    // Default anchor class, not applied if value is null
    'anchor_class'                  => null,

    // Default active anchor class, not applied if value is null
    'active_anchor_class'           => null,

    // Default sort direction anchor class, not applied if value is null
    'direction_anchor_class_prefix' => null,

    // Relation - column separator ex: author.name means relation "author" and column "name"
    'relation_column_separator'     => '.',

    // Formatting function applied to name of column, use null to turn formatting off
    'formatting_function'           => 'ucfirst',

    // Apply formatting function to custom titles as well as column names
    'format_custom_titles'          => true,

    // Inject title parameter in query strings, use null to turn injection off
    // Example: 'inject_title' => 't' will result in ..user/?t="formatted title of sorted column"
    'inject_title_as'               => null,

    // Allow request modification, when default sorting is set but is not in URI (first load)
    'allow_request_modification'    => true,

    // Default direction for: $user->sortable('id') usage
    'default_direction'             => 'asc',

    // Default direction for non-sorted columns
    'default_direction_unsorted'    => 'asc',

    // Use the first defined sortable column (Model::$sortable) as default
    // Also applies if sorting parameters are invalid for example: 'sort' => 'name', 'direction' => ''
    'default_first_column'          => false,

    // Join type: join vs leftJoin
    'join_type'                     => 'leftJoin',

];
