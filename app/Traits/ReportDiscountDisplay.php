<?php

namespace App\Traits;

/**
 * The setting of an income or expense report that says how it shows the discount on the document total on the
 * accrual basis by category: shared out over the categories (spread), or on a row of its own, sales discounts under
 * income and purchase discounts under the costs, with the categories at their amount before it (separate).
 */
trait ReportDiscountDisplay
{
    public const DISCOUNT_DISPLAY_SPREAD = 'spread';

    public const DISCOUNT_DISPLAY_SEPARATE = 'separate';

    /**
     * The row the discount takes when it shows on a row of its own.
     */
    public const DISCOUNT_ROW = 'discount';

    /**
     * A saved setting of the report, which the display options of the Customise panel also offer as a switch for
     * one run, as it changes how the figures are listed, not what they add up to. It applies on the
     * accrual basis by category only: a payment has the discount taken off already, and a contact or an item has
     * no row for it, so the bar leaves it out otherwise.
     */
    public function getDiscountDisplayField(): array
    {
        return [
            'type' => 'select',
            'name' => 'discount_display',
            'title' => trans('reports.discount_display.title'),
            'icon' => 'sell',
            'values' => [
                static::DISCOUNT_DISPLAY_SPREAD => trans('reports.discount_display.spread'),
                static::DISCOUNT_DISPLAY_SEPARATE => trans('reports.discount_display.separate'),
            ],
            'selected' => static::DISCOUNT_DISPLAY_SPREAD,
            'placement' => 'display',
            'applies' => $this->canShowDiscountRow(),
            'attributes' => [
                'required' => 'required',
            ],
        ];
    }

    public function getDiscountDisplay(): string
    {
        return (string) $this->getFieldValue('discount_display', 'getDiscountDisplayField');
    }

    /**
     * Whether the discount shows on a row of its own, where the setting applies (getDiscountDisplayField()).
     */
    public function showsDiscountRow(): bool
    {
        return $this->canShowDiscountRow() && ($this->getDiscountDisplay() === static::DISCOUNT_DISPLAY_SEPARATE);
    }

    protected function canShowDiscountRow(): bool
    {
        return ($this->getBasis() !== 'cash') && ($this->getGroup() === 'category');
    }
}
