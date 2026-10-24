<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Reconciliation on the account list
    |--------------------------------------------------------------------------
    |
    | The card of an account kept by hand counts what is still waiting to be reconciled
    | since the account's last reconciliation (everything, on one never reconciled) and
    | offers to reconcile it, a month at a time, or to carry on with a draft. Turning it
    | off takes everything about reconciling off the card: the button, the count and the
    | last statement. An account kept up to date by an app (bank feeds) shows that app's
    | own actions instead and is not affected.
    |
    */

    'reconciliation' => [

        'enabled'   => env('ACCOUNTS_RECONCILIATION_ENABLED', true),

    ],

    /*
    |--------------------------------------------------------------------------
    | Balance history on the account list
    |--------------------------------------------------------------------------
    |
    | How many days the balance line drawn on each account card covers.
    |
    */

    'balance_history_days' => env('ACCOUNTS_BALANCE_HISTORY_DAYS', 30),

];
