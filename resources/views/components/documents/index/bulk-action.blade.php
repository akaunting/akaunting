@if (! $hideContactName || ! $hideCurrency || ! $hideCategory)
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        @if (! $hideContactName)
            <x-form.group.contact
                :type="$contact_type"
                @option="onChangeContactCard($event.option)"
                without-add-new
                not-required
                form-group-class="sm:col-span-6"
            />
        @endif

        @if (! $hideCategory)
            <x-form.group.category
                :type="$category_type"
                without-add-new
                selected=""
                not-required
                form-group-class="sm:col-span-6"
            />
        @endif

        @if (! $hideCurrency)
            <x-form.group.currency
                without-add-new
                selected=""
                not-required
                form-group-class="sm:col-span-6"
            />
        @endif
    </div>
@endif
