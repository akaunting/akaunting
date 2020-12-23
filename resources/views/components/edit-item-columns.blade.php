<akaunting-edit-item-columns
    placeholder="{{ (!empty($filters)) ? trans('general.placeholder.search_and_filter') : trans('general.search_placeholder')}}"
    search-text="{{ trans('general.search_text') }}"
    :edit-column="{{ json_encode([
        'status' => true,
        'text' => trans('general.add_new'),
        'new_text' => trans('modules.new'),
        'buttons' => [
            'cancel' => [
                'text' => trans('general.cancel'),
                'class' => 'btn-outline-secondary'
            ],
            'confirm' => [
                'text' => trans('general.save'),
                'class' => 'btn-success'
            ]
        ]
    ])}}"
></akaunting-edit-item-columns>