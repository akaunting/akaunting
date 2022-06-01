@switch($template)
    @case('second')
        <x-transfers.template.second :transfer="$transfer" />
        @break
    @case('third')
        <x-transfers.template.third :transfer="$transfer" />
        @break
    @default
        <x-transfers.template.ddefault :transfer="$transfer" />
@endswitch
