@props(['invoice'])

<div class="flex">
    <div class="w-full text-center">
        <div class="my-10">
            <img src="https://assets.akaunting.com/software/portal/finish.gif" alt="Get Started" />
        </div>

        <div class="my-10">
            {{ trans('portal.create_your_invoice') }}
        </div>

        <div class="my-10">
            <a href="https://akaunting.com/lp/accounting-software?utm_source=invoice_payment&utm_medium=software&utm_campaign=plg" class="bg-purple text-white px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 hover:bg-purple-700">
                {{ trans('portal.get_started') }}
            </a>
        </div>
    </div>
</div>
