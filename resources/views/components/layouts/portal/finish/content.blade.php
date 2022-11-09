@props(['invoice'])

<div class="flex">
    <div class="w-full text-center">
        <div class="my-10">
            {{ trans('portal.create_your_invoice') }}
        </div>

        <div class="my-10">
            <x-link href="https://akaunting.com/accounting-software?utm_source=software&utm_medium=invoice_payment&utm_campaign=plg" class="bg-purple text-white px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 hover:bg-purple-700" override="class">
                {{ trans('portal.get_started') }}
            </x-link>
        </div>

        <div class="my-10">
            <img src="https://assets.akaunting.com/software/portal/finish.gif" class="inline" alt="Get Started" />
        </div>
    </div>
</div>
