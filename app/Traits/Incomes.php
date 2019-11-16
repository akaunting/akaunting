<?php

namespace App\Traits;

trait Incomes
{
    /**
     * Generate next invoice number
     *
     * @return string
     */
    public function getNextInvoiceNumber()
    {
        $prefix = setting('invoice.number_prefix', 'INV-');
        $next = setting('invoice.number_next', '1');
        $digit = setting('invoice.number_digit', '5');

        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);

        return $number;
    }

    /**
     * Increase the next invoice number
     */
    public function increaseNextInvoiceNumber()
    {
        $next = setting('invoice.number_next', 1) + 1;

        setting(['invoice.number_next' => $next]);
        setting()->save();
    }
}