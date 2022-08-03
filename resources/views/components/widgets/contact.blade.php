<div class="flex items-center">
    <div class="w-12 h-12 flex items-center justify-center text-2xl font-regular border border-gray-300 rounded-full p-6">
        {{ $shortName($contact->name) }}
    </div>

    <div class="flex flex-col text-black-400 text-sm font-medium ltr:ml-4 rtl:mr-4">
        @if ($contact->email)
            <span>{{ $contact->email }}</span>
        @endif

        @if ($contact->phone)
            <span>{{ $contact->phone }}</span>
        @endif
    </div>
</div>

<div class="mt-12 text-black-400">
    <div class="flex flex-col text-sm mb-5">
        <div class="font-medium text-black">
            {{ trans('portal.billing_address') }}
        </div>

        <span>
            @if ($contact->address)
                {{ $contact->address }} </br>
            @endif

            @if ($contact->state)
                {{ $contact->state }},
            @endif

            @if ($contact->country)
                <x-index.country code="{{ $contact->country }}" />
            @endif
        </span>
    </div>

    <div class="flex flex-col text-sm mb-5">
        <div class="font-medium text-black">
            {{ trans('general.tax_number') }}
        </div>

        @if ($contact->tax_number)
            <span> {{ $contact->tax_number }} </span>
        @endif
    </div>

    <x-link href="{{ route('portal.profile.edit', user()->id) }}" class="border-b text-sm hover:text-black" override="class">
        {{ trans('portal.see_all_details') }}
    </x-link>
</div>
