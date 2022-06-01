@props(['invoice'])

@stack('button_pdf_start')
<x-link href="{{ route('portal.invoices.pdf', $invoice->id) }}" class="bg-green text-white px-3 py-1.5 mb-3 sm:mb-0 rounded-lg text-sm font-medium leading-6 hover:bg-green-700">
    {{ trans('general.download_pdf') }}
</x-link>
@stack('button_pdf_end')

@stack('button_show_start')
<x-link href="{{ route('portal.invoices.show', $invoice->id) }}" class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium leading-6">
    {{ trans('general.show') }}
</x-link>
@stack('button_show_end')

@stack('button_print_start')
<x-link href="{{ route('portal.invoices.print', $invoice->id) }}" target="_blank" class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium leading-6">
    {{ trans('general.print') }}
</x-link>
@stack('button_print_end')
