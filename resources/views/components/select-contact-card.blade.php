<akaunting-contact-card
    placeholder="{{ $placeholder }}"
    no-data-text="{{ trans('general.no_data') }}"
    no-matching-data-text="{{ trans('general.no_matching_data') }}"
    search-route="{{ $search_route }}"
    create-route="{{ $create_route }}"
    :contacts="{{ json_encode($contacts) }}"
    :selected="{{ json_encode($contact) }}"
    add-contact-text="{{ $textAddContact }}"
    create-new-contact-text="{{ $textCreateNewContact }}"
    editContactText="{{ $textEditContact }}"
    contact-info-text="{{ $textContactInfo }}"
    tax-number-text="{{ trans('general.tax_number') }}"
    choose-different-contact-text="{{ $textChooseDifferentContact }}"
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
