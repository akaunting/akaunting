<akaunting-contact-card
    placeholder="{{ $placeholder }}"
    no-data-text="{{ trans('general.no_data') }}"
    no-matching-data-text="{{ trans('general.no_matching_data') }}"
    search-route="{{ $search_route }}"
    create-route="{{ $create_route }}"
    :contacts="{{ json_encode($contacts) }}"
    :selected="{{ json_encode($contact) }}"

    :add-new="{{ json_encode([
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

    @change="onChangeContactCard"
></akaunting-contact-card>
