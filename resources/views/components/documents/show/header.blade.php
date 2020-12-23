<div class="row" style="font-size: inherit !important">
    @if (!$hideHeaderStatus)
    <div class="{{ $classHeaderStatus }}">
        {{ trans_choice('general.statuses', 1) }}
        <br>
        
        <strong>
            <span class="float-left">
                <span class="badge badge-{{ $document->status_label }}">
                    {{ trans($textHistoryStatus . $document->status) }}
                </span>
            </span>
        </strong>
        <br><br>
    </div>
    @endif

    @if (!$hideHeaderContact)
    <div class="{{ $classHeaderContact }}">
        {{ trans_choice($textHeaderContact, 1) }}
        <br>

        <strong>
            <span class="float-left">
                {{ $document->contact_name }}
            </span>
        </strong>
        <br><br>
    </div>
    @endif

    @if (!$hideHeaderAmount)
    <div class="{{ $classHeaderAmount }}">
        {{ trans($textHeaderAmount) }}
        <br>

        <strong>
            <span class="float-left">
                @money($document->amount - $document->paid, $document->currency_code, true)
            </span>
        </strong>
        <br><br>
    </div>
    @endif

    @if (!$hideHeaderDueAt)
    <div class="{{ $classHeaderDueAt }}">
        {{ trans($textHeaderDueAt) }}
        <br>

        <strong>
            <span class="float-left">
                @date($document->due_at)
            </span>
        </strong>
        <br><br>
    </div>
    @endif
</div>