<?php

namespace App\Abstracts;

use App\Abstracts\Model;
use App\Models\Setting\Tax;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Media;
use App\Traits\Recurring;
use Bkwld\Cloner\Cloneable;

abstract class DocumentModel extends Model
{
    use Cloneable, Currencies, DateTime, Media, Recurring;

    public function totals_sorted()
    {
        return $this->totals()->orderBy('sort_order');
    }

    public function scopeDue($query, $date)
    {
        return $query->whereDate('due_at', '=', $date);
    }

    public function scopeAccrued($query)
    {
        return $query->whereNotIn('status', ['draft', 'cancelled']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', '=', 'paid');
    }

    public function scopeNotPaid($query)
    {
        return $query->where('status', '<>', 'paid');
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getAttachmentAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('attachment')) {
            return $value;
        } elseif (!$this->hasMedia('attachment')) {
            return false;
        }

        return $this->getMedia('attachment')->last();
    }

    /**
     * Get the discount percentage.
     *
     * @return string
     */
    public function getDiscountAttribute()
    {
        $percent = 0;

        $discount = $this->totals->where('code', 'discount')->makeHidden('title')->pluck('amount')->first();

        if ($discount) {
            $sub_total = $this->totals->where('code', 'sub_total')->makeHidden('title')->pluck('amount')->first();

            $percent = number_format((($discount * 100) / $sub_total), 0);
        }

        return $percent;
    }

    /**
     * Get the paid amount.
     *
     * @return string
     */
    public function getPaidAttribute()
    {
        if (empty($this->amount)) {
            return false;
        }

        $paid = 0;
        $reconciled = $reconciled_amount = 0;

        $code = $this->currency_code;
        $rate = config('money.' . $code . '.rate');
        $precision = config('money.' . $code . '.precision');

        if ($this->transactions->count()) {
            foreach ($this->transactions as $item) {
                $amount = $item->amount;

                if ($code != $item->currency_code) {
                    $amount = $this->convertBetween($amount, $item->currency_code, $item->currency_rate, $code, $rate);
                }

                $paid += $amount;

                if ($item->reconciled) {
                    $reconciled_amount = +$amount;
                }
            }
        }

        if (bccomp(round($this->amount, $precision), round($reconciled_amount, $precision), $precision) === 0) {
            $reconciled = 1;
        }

        $this->setAttribute('reconciled', $reconciled);

        return round($paid, $precision);
    }

    /**
     * Get the status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'paid':
                $label = 'success';
                break;
            case 'partial':
                $label = 'info';
                break;
            case 'sent':
            case 'received':
                $label = 'danger';
                break;
            case 'viewed':
                $label = 'warning';
                break;
            case 'cancelled':
                $label = 'dark';
                break;
            default:
                $label = 'primary';
                break;
        }

        return $label;
    }

    /**
     * Get the amount without tax.
     *
     * @return string
     */
    public function getAmountWithoutTaxAttribute()
    {
        $amount = $this->amount;

        $this->totals->where('code', 'tax')->each(function ($total) use(&$amount) {
            $tax = Tax::name($total->name)->first();

            if (!empty($tax) && ($tax->type == 'withholding')) {
                return;
            }

            $amount -= $total->amount;
        });

        return $amount;
    }

    /**
     * Convert amount to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (double) $value;
    }

    /**
     * Convert currency rate to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setCurrencyRateAttribute($value)
    {
        $this->attributes['currency_rate'] = (double) $value;
    }
}
