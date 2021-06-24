<div class="card show-card" style="padding: 0; padding-left: 15px; padding-right: 15px; border-radius: 0; box-shadow: 0 4px 16px rgba(0,0,0,.2);">
    <div class="card-body show-card-body">
        @if ($transactionTemplate)
            @switch($transactionTemplate)
                @case('classic')
                    @break
                @case('modern')
                    @break  
                @default
                    <x-transactions.template.ddefault
                        type="{{ $type }}"
                        :transaction="$transaction"
                        :payment_methods="$payment_methods"
                        transaction-template="{{ $transactionTemplate }}"
                        logo="{{ $logo }}"
                    />
            @endswitch
        @else
            @include($transactionTemplate)
        @endif
    </div>
</div>
