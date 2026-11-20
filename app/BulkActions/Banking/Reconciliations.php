<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Jobs\Banking\DeleteReconciliation;
use App\Jobs\Banking\UpdateReconciliation;
use App\Models\Banking\Reconciliation;
use App\Traits\Reconciliations as ReconciliationRules;

class Reconciliations extends BulkAction
{
    use ReconciliationRules;

    public $model = Reconciliation::class;

    public $text = 'general.reconciliations';

    public $path = [
        'group' => 'banking',
        'type' => 'reconciliations',
    ];

    public $actions = [
        'reconcile'     => [
            'icon'          => 'published_with_changes',
            'name'          => 'reconciliations.reconcile',
            'message'       => 'bulk_actions.message.reconcile',
            'permission'    => 'update-banking-reconciliations',
        ],
        'unreconcile'   => [
            'icon'          => 'layers_clear',
            'name'          => 'reconciliations.unreconcile',
            'message'       => 'bulk_actions.message.unreconcile',
            'permission'    => 'update-banking-reconciliations',
        ],
        'delete'        => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-banking-reconciliations',
        ],
    ];

    /**
     * Reconciles the drafts as the Reconcile button of their edit page does, with the transactions checked on them:
     * a transaction left unchecked, i.e. one not on the bank statement, stays open.
     */
    public function reconcile($request)
    {
        $reconciliations = $this->getSelectedRecords($request);

        foreach ($reconciliations as $reconciliation) {
            if ($reconciliation->reconciled) {
                continue;
            }

            // Another reconciled one cleared the transactions of the period (Models\Banking\Reconciliation::scopeReconciledOverlapping())
            $overlapping = Reconciliation::reconciledOverlapping(
                $reconciliation->account_id,
                $reconciliation->started_at,
                $reconciliation->ended_at,
                $reconciliation->id
            )->first();

            if ($overlapping) {
                flash($reconciliation->period . ': ' . trans('reconciliations.overlapping', ['period' => $overlapping->period]))->warning()->important();

                continue;
            }

            // As the page lets it be reconciled: the bank statement and the books agree once the checked transactions
            // are cleared. A period without transactions too, when its closing balance is the opening one
            $account = $reconciliation->account;

            // An account deleted since, there is nothing to reconcile it with
            if (! $account->exists) {
                continue;
            }

            $checked = $this->getReconcilableTransactions($reconciliation->account_id, $reconciliation->started_at, $reconciliation->ended_at, $reconciliation->id)
                ->whereIn('id', $this->getCheckedTransactionIds($reconciliation->transactions))
                ->get();

            $difference = $this->getReconciliationDifference($account, $reconciliation->started_at, $reconciliation->closing_balance, $checked);

            if ($difference != 0) {
                flash(trans('reconciliations.difference_not_zero', [
                    'period' => $reconciliation->period,
                    'difference' => money($difference, $account->currency_code)->format(),
                ]))->warning()->important();

                continue;
            }

            try {
                // The checked transactions are the ones it was saved with
                $this->dispatch(new UpdateReconciliation($reconciliation, ['reconcile' => 1]));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function unreconcile($request)
    {
        $reconciliations = $this->getSelectedRecords($request);

        foreach ($reconciliations as $reconciliation) {
            // Same as deleting it, one that isn't reconciled hasn't cleared any transactions
            if (! $reconciliation->reconciled) {
                continue;
            }

            try {
                $this->dispatch(new UpdateReconciliation($reconciliation, ['reconcile' => 0]));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function destroy($request)
    {
        $reconciliations = $this->getSelectedRecords($request);

        foreach ($reconciliations as $reconciliation) {
            try {
                $this->dispatch(new DeleteReconciliation($reconciliation));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
