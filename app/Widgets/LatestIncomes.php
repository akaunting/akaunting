<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Banking\Transaction;

class LatestIncomes extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'width' => 'col-md-4'
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //
        $latest_incomes = Transaction::type('income')->orderBy('paid_at', 'desc')->isNotTransfer()->take(5)->get();

        return view('widgets.latest_incomes', [
            'config' => (object) $this->config,
            'latest_incomes' => $latest_incomes,
        ]);
    }
}