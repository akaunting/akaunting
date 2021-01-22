<akaunting-contact-card
    placeholder="{{ $placeholder }}"
    no-data-text="{{ trans('general.no_data') }}"
    no-matching-data-text="{{ trans('general.no_matching_data') }}"
    search-route="{{ $search_route }}"
    create-route="{{ $create_route }}"
    :contacts="{{ json_encode($contacts) }}"
    :selected="{{ json_encode($contact) }}"
    add-contact-text="{{ is_array($textAddContact) ? trans($textAddContact[0], ['field' => trans_choice($textAddContact[1], 1)]) : trans($textAddContact) }}"
    create-new-contact-text="{{ is_array($textCreateNewContact) ? trans($textCreateNewContact[0], ['field' => trans_choice($textCreateNewContact[1], 1)]) : trans($textCreateNewContact) }}"
    edit-contact-text="{{ is_array($textEditContact) ? trans($textEditContact[0], ['field' => trans_choice($textEditContact[1], 1)]) : trans($textEditContact) }}"
    contact-info-text="{{ is_array($textContactInfo) ? trans($textContactInfo[0], ['field' => trans_choice($textContactInfo[1], 1)]) : trans($textContactInfo) }}"
    tax-number-text="{{ trans('general.tax_number') }}"
    choose-different-contact-text="{{ is_array($textChooseDifferentContact) ? trans($textChooseDifferentContact[0], ['field' => Str::lower(trans_choice($textChooseDifferentContact[1], 1))]) : trans($textChooseDifferentContact) }}"
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
    :error="{{ $error }}"

    @change="onChangeContactCard"
></akaunting-contact-card>
