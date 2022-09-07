<div class="flex flex-col text-sm text-black-400">
    <div class="font-medium text-black mb-1">
        {{ trans('portal.invoice_history.title') }}
    </div>

    @if ($invoices->count())
        @foreach ($invoices as $item)
        <span class="text-xs mb-3">
            {{ trans('portal.invoice_history.description', ['date' => company_date($item->due_at), 'invoice_number' => $item->document_number]) }}
        </span>
        @endforeach

        @if ($invoices->count() > 2)
        <x-link href="{{ route('portal.invoices.index') }}" class="underline hover:text-black-700" override="class">
            {{ trans('modules.see_all_type', ['type' => trans_choice('general.invoices', 2)]) }}
        </x-link>
        @endif
    @else
        <span class="text-xs">
            {{ trans('portal.invoice_history.no_data') }}
        </span>
    @endif
</div>
