<?php

namespace App\Http\Requests\Banking;

use App\Abstracts\Http\FormRequest;
use App\Models\Banking\Account;
use App\Models\Banking\Reconciliation as Model;
use App\Models\Banking\Transaction;
use App\Traits\Reconciliations;
use App\Utilities\Date;

/**
 * What the page sends is checked on the page too, what the API sends only here, so the rules of the page are held
 * here: the period of a reconciliation can not overlap a reconciled one, it lists the transactions of its account in
 * its period only, and it is reconciled when the bank statement and the books agree.
 */
class Reconciliation extends FormRequest
{
    use Reconciliations;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'reconcile' => 'nullable|boolean',
            'transactions' => 'nullable|array',
        ];

        // An update changes whether it is reconciled and its checked transactions only (UpdateReconciliation), its
        // account, period and closing balance stay the ones it was created with, so the API need not send them
        if ($this->getReconciliation()) {
            return $rules;
        }

        $company_id = (int) $this->request->get('company_id', company_id());

        return $rules + [
            'account_id' => 'required|integer|exists:accounts,id,company_id,' . $company_id . ',deleted_at,NULL',
            'started_at' => 'required|date_format:Y-m-d H:i:s|before_or_equal:ended_at',
            'ended_at' => 'required|date_format:Y-m-d H:i:s|after_or_equal:started_at',
            'closing_balance' => 'required',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // The rules above report what can't be read
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $reconciliation = $this->getReconciliation();

            $account_id = $reconciliation ? $reconciliation->account_id : $this->input('account_id');
            $started_at = Date::parse($reconciliation ? $reconciliation->started_at : $this->input('started_at'))->startOfDay();
            $ended_at = Date::parse($reconciliation ? $reconciliation->ended_at : $this->input('ended_at'))->endOfDay();
            $except_id = $reconciliation?->id;

            $overlapping = Model::reconciledOverlapping($account_id, $started_at, $ended_at, $except_id)->first();

            if ($overlapping) {
                $validator->errors()->add('started_at', trans('reconciliations.overlapping', ['period' => $overlapping->period]));

                return;
            }

            // The ones sent, the ones it was saved with are left to the jobs, which skip a transaction not in the
            // period any more, i.e. an unchecked one whose date was changed
            if ($this->has('transactions')) {
                $invalid = $this->getTransactionsNotInThePeriod((array) $this->input('transactions'), $account_id, $started_at, $ended_at);

                if ($invalid) {
                    $validator->errors()->add('transactions', trans('reconciliations.not_reconcilable', ['transactions' => implode(', ', $invalid)]));

                    return;
                }
            }

            // Reconciled by this request, the page lets it be when the difference is 0
            if (! $this->has('reconcile') || ! filter_var($this->input('reconcile'), FILTER_VALIDATE_BOOLEAN)) {
                return;
            }

            $transactions = $this->has('transactions') ? $this->input('transactions') : $reconciliation?->transactions;

            $account = Account::find($account_id);

            // An account deleted since, the rules above refuse a new reconciliation of one
            if (! $account) {
                return;
            }

            $checked = $this->getReconcilableTransactions($account_id, $started_at, $ended_at, $except_id)
                ->whereIn('id', $this->getCheckedTransactionIds($transactions))
                ->get();

            $closing_balance = $reconciliation ? $reconciliation->closing_balance : $this->input('closing_balance');

            $difference = $this->getReconciliationDifference($account, $started_at, $closing_balance, $checked);

            if ($difference != 0) {
                $validator->errors()->add('reconcile', trans('reconciliations.difference_not_zero', [
                    'period' => company_date($started_at) . ' - ' . company_date($ended_at),
                    'difference' => money($difference, $account->currency_code)->format(),
                ]));
            }
        });
    }

    /**
     * The keys of the transactions that are not of the account in the period, the ones the page does not list. A
     * key is the type and id of the transaction, i.e. "income_12". A transaction that is not there any more, i.e. deleted while the page was
     * open, is left out as the jobs leave it out.
     *
     * @return array<string>
     */
    protected function getTransactionsNotInThePeriod(array $transactions, $account_id, $started_at, $ended_at): array
    {
        $invalid = $ids = [];

        foreach (array_keys($transactions) as $key) {
            $id = $this->getReconciliationTransactionId($key);

            if (is_null($id)) {
                $invalid[] = (string) $key;

                continue;
            }

            $ids[$id] = (string) $key;
        }

        if (empty($ids)) {
            return $invalid;
        }

        $found = Transaction::whereIn('id', array_keys($ids))->get(['id', 'account_id', 'paid_at']);

        foreach ($found as $transaction) {
            $is_in = ((int) $transaction->account_id === (int) $account_id)
                && $transaction->paid_at->between($started_at, $ended_at);

            if (! $is_in) {
                $invalid[] = $ids[$transaction->id];
            }
        }

        return $invalid;
    }

    /**
     * The reconciliation being updated, none when one is created.
     */
    protected function getReconciliation(): ?Model
    {
        $reconciliation = $this->route('reconciliation');

        if ($reconciliation instanceof Model) {
            return $reconciliation;
        }

        return ! empty($reconciliation) ? Model::find($reconciliation) : null;
    }
}
