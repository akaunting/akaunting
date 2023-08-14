<x-form.accordion type="company" :open="(! $hideLogo && empty(setting('company.logo')))">
    <x-slot name="head">
        <x-form.accordion.head
            title="{{ trans_choice($textSectionCompaniesTitle, 1) }}"
            description="{{ trans($textSectionCompaniesDescription) }}"
        />
    </x-slot>

    <x-slot name="body">
        <div class="sm:col-span-2 grid gap-x-8 gap-y-6">
            @stack('title_start')

            @if (! $hideDocumentTitle)
                <x-form.group.text
                    name="title"
                    label="{{ trans('settings.invoice.title') }}"
                    value="{{ $title }}"
                    not-required
                 />
            @endif

            @stack('subheading_start')

            @if (! $hideDocumentSubheading)
                <x-form.group.text
                    name="subheading"
                    label="{{ trans('settings.invoice.subheading') }}"
                    value="{{ $subheading }}"
                    not-required
                 />
            @endif
        </div>

        <div class="sm:col-span-2">
            @if (! $hideLogo)
                <x-form.input.hidden name="company_logo" data-field="setting" />
                <x-form.group.file name="company_logo" label="{{ trans('settings.company.logo') }}" :value="setting('company.logo')" not-required data-field="setting" />
            @endif
        </div>

        <div class="sm:col-span-2 relative">
            @if (! $hideCompanyEdit)
                <akaunting-company-edit
                    company-id="{{ company_id() }}"
                    button-text="{{ trans('settings.company.edit_your_business_address') }}"
                    tax-number-text="{{ trans('general.tax_number') }}"
                    :company="{{ json_encode($company) }}"
                    :company-form="{{ json_encode([
                        'show' => true,
                        'text' => trans('settings.company.edit_your_business_address'),
                        'buttons' => [
                            'cancel' => [
                                'text' => trans('general.cancel'),
                                'class' => 'btn-outline-secondary'
                            ],
                            'confirm' => [
                                'text' => trans('general.save'),
                                'class' => 'disabled:bg-green-100'
                            ]
                        ]
                    ])}}"
                ></akaunting-company-edit>
            @endif
        </div>
    </x-slot>
</x-form.accordion>
