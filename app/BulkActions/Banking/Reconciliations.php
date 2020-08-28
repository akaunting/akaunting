<?php

namespace App\BulkActions\Banking;

use App\Abstracts\BulkAction;
use App\Models\Banking\Reconciliation;
use App\Models\Banking\Transaction;

class Reconciliations extends BulkAction
{
    public $model = Reconciliation::class;

    public $actions = [
        'reconcile' => [
            'name' => 'reconciliations.reconcile',
            'message' => 'bulk_actions.message.reconcile',
            'permission' => 'update-banking-reconciliations',
        ],
        'unreconcile' => [
            'name' => 'reconciliations.unreconcile',
            'message' => 'bulk_actions.message.unreconcile',
            'permission' => 'update-banking-reconciliations',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-banking-reconciliations',
        ],
    ];

    public function reconcile($request)
    {
        $reconciliations = $this->getSelectedRecords($request);

        foreach ($reconciliations as $reconciliation) {
            \DB::transaction(function () use ($reconciliation) {
                $reconciliation->reconciled = 1;
                $reconciliation->save();

                Transaction::where('account_id', $reconciliation->account_id)
                    ->isNotReconciled()
                    ->whereBetween('paid_at', [$reconciliation->started_at, $reconciliation->ended_at])->each(function ($item) {
                        $item->reconciled = 1;
                        $item->save();
                    });
            });
        }
    }

    public function unreconcile($request)
    {
        $reconciliations = $this->getSelectedRecords($request);

        foreach ($reconciliations as $reconciliation) {
            \DB::transaction(function () use ($reconciliation) {
                $reconciliation->reconciled = 0;
                $reconciliation->save();

                Transaction::where('account_id', $reconciliation->account_id)
                    ->isReconciled()
                    ->whereBetween('paid_at', [$reconciliation->started_at, $reconciliation->ended_at])->each(function ($item) {
                        $item->reconciled = 0;
                        $item->save();
                    });
            });
        }
    }

    public function destroy($request)
    {
        $reconciliations = $this->getSelectedRecords($request);

        foreach ($reconciliations as $reconciliation) {
            \DB::transaction(function () use ($reconciliation) {
                $reconciliation->delete();

                Transaction::where('account_id', $reconciliation->account_id)
                    ->isReconciled()
                    ->whereBetween('paid_at', [$reconciliation->started_at, $reconciliation->ended_at])->each(function ($item) {
                        $item->reconciled = 0;
                        $item->save();
                    });
            });
        }
    }
}
