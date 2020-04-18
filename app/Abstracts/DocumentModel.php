<?php

namespace App\Abstracts;

use App\Abstracts\Model;
use App\Models\Banking\Transaction;
use App\Models\Setting\Currency;
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

        $discount = $this->totals()->where('code', 'discount')->value('amount');

        if ($discount) {
            $sub_total = $this->totals()->where('code', 'sub_total')->value('amount');

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

        if ($this->transactions->count()) {
            $currencies = Currency::enabled()->pluck('rate', 'code')->toArray();

            foreach ($this->transactions as $item) {
                if ($this->currency_code == $item->currency_code) {
                    $amount = (double) $item->amount;
                } else {
                    $default_model = new Transaction();
                    $default_model->default_currency_code = $this->currency_code;
                    $default_model->amount = $item->amount;
                    $default_model->currency_code = $item->currency_code;
                    $default_model->currency_rate = $currencies[$item->currency_code];

                    $default_amount = (double) $default_model->getAmountConvertedToDefault();

                    $convert_model = new Transaction();
                    $convert_model->default_currency_code = $item->currency_code;
                    $convert_model->amount = $default_amount;
                    $convert_model->currency_code = $this->currency_code;
                    $convert_model->currency_rate = $currencies[$this->currency_code];

                    $amount = (double) $convert_model->getAmountConvertedFromDefault();
                }

                $paid += $amount;

                if ($item->reconciled) {
                    $reconciled_amount = +$amount;
                }
            }
        }

        if ($this->amount == $reconciled_amount) {
            $reconciled = 1;
        }

        $this->setAttribute('reconciled', $reconciled);

        return $paid;
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

        $this->totals()->where('code', 'tax')->each(function ($tax) use(&$amount) {
            $amount -= $tax->amount;
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
