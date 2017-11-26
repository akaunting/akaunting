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
        $prefix = setting('general.invoice_number_prefix', 'INV-');
        $next = setting('general.invoice_number_next', '1');
        $digit = setting('general.invoice_number_digit', '5');

        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);

        return $number;
    }

    /**
     * Increase the next invoice number
     */
    public function increaseNextInvoiceNumber()
    {
        // Update next invoice number
        $next = setting('general.invoice_number_next', 1) + 1;
        setting(['general.invoice_number_next' => $next]);
        setting()->save();
    }
}