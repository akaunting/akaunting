<?php

namespace App\View\Components\Documents\Form;

use Illuminate\View\Component;

use App\Models\Setting\Currency;
use App\Models\Setting\Tax;

class Items extends Component
{
    /** @var string */
    public $type;

    public $document;

    /** @var bool */
    public $hideEditItemColumns;

    /** @var string */
    public $textItems;

    /** @var string */
    public $textQuantity;

    /** @var string */
    public $textPrice;

    /** @var string */
    public $textAmount;

    /** @var bool */
    public $hideItems;

    /** @var bool */
    public $hideName;

    /** @var bool */
    public $hideDescription;

    /** @var bool */
    public $hideQuantity;

    /** @var bool */
    public $hidePrice;

    /** @var bool */
    public $hideDiscount;

    /** @var bool */
    public $hideAmount;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type, $document = false,
        string $textItems = '', string $textQuantity = '', string $textPrice = '', string $textAmount = '',
        bool $hideItems = false, bool $hideName = false, bool $hideDescription = false, bool $hideQuantity = false,
        bool $hidePrice = false, bool $hideDiscount = false, bool $hideAmount = false,
        bool $hideEditItemColumns = false
    ) {
        $this->type = $type;
        $this->document = $document;

        $this->textItems = $this->getTextItems($type, $textItems);
        $this->textQuantity = $this->getTextQuantity($type, $textQuantity);
        $this->textPrice = $this->getTextPrice($type, $textPrice);
        $this->textAmount = $this->getTextAmount($type, $textAmount);

        $this->hideItems = $this->getHideItems($type, $hideItems, $hideName, $hideDescription);
        $this->hideName = $this->getHideName($type, $hideName);
        $this->hideDescription = $this->getHideDescription($type, $hideDescription);
        $this->hideQuantity = $this->getHideQuantity($type, $hideQuantity);
        $this->hidePrice = $this->getHidePrice($type, $hidePrice);
        $this->hideDiscount = $this->getHideDiscount($type, $hideDiscount);
        $this->hideAmount = $this->getHideAmount($type, $hideAmount);

        $this->hideEditItemColumns = $hideEditItemColumns;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $currency = Currency::where('code', setting('default.currency'))->first();

        $taxes = Tax::enabled()->orderBy('name')->get();

        return view('components.documents.form.items', compact('currency', 'taxes'));
    }

    protected function getTextItems($type, $text_items)
    {
        if (!empty($text_items)) {
            return $text_items;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $text_items = setting('invoice.item_name', 'general.items');

                if ($text_items == 'custom') {
                    $text_items = setting('invoice.item_name_input');
                }
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $text_items = 'general.items';
                break;
        }

        return $text_items;
    }

    protected function getTextQuantity($type, $text_quantity)
    {
        if (!empty($text_quantity)) {
            return $text_quantity;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $text_quantity = setting('invoice.quantity_name', 'invoices.quantity');

                if ($text_quantity == 'custom') {
                    $text_quantity = setting('invoice.quantity_name_input');
                }
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $text_quantity = 'bills.quantity';
                break;
        }

        return $text_quantity;
    }

    protected function getTextPrice($type, $text_price)
    {
        if (!empty($text_price)) {
            return $text_price;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $text_price = setting('invoice.price_name', 'invoices.price');

                if ($text_price == 'custom') {
                    $text_price = setting('invoice.price_name_input');
                }
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $text_price = 'bills.price';
                break;
        }

        return $text_price;
    }

    protected function getTextAmount($type, $text_amount)
    {
        if (!empty($text_amount)) {
            return $text_amount;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
            case 'bill':
            case 'expense':
            case 'purchase':
                $text_amount = 'general.amount';
                break;
        }

        return $text_amount;
    }

    protected function getHideItems($type, $hideItems, $hideName, $hideDescription)
    {
        if (!empty($hideItems)) {
            return $hideItems;
        }

        $hideItems = ($this->getHideName($type, $hideName) & $this->getHideDescription($type, $hideDescription)) ? true  : false;

        return $hideItems;
    }

    protected function getHideName($type, $hideName)
    {
        if (!empty($hideName)) {
            return $hideName;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $hideName = setting('invoice.hide_item_name', $hideName);
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $hideName = setting('bill.hide_item_name', $hideName);
                break;
        }

        return $hideName;
    }

    protected function getHideDescription($type, $hideDescription)
    {
        if (!empty($hideDescription)) {
            return $hideDescription;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $hideDescription = setting('invoice.hide_item_description', $hideDescription);
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $hideDescription = setting('bill.hide_item_description', $hideDescription);
                break;
        }

        return $hideDescription;
    }

    protected function getHideQuantity($type, $hideQuantity)
    {
        if (!empty($hideQuantity)) {
            return $hideQuantity;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $hideQuantity = setting('invoice.hide_quantity', $hideQuantity);
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $hideQuantity = setting('bill.hide_quantity', $hideQuantity);
                break;
        }

        return $hideQuantity;
    }

    protected function getHidePrice($type, $hidePrice)
    {
        if (!empty($hidePrice)) {
            return $hidePrice;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $hidePrice = setting('invoice.hide_price', $hidePrice);
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $hidePrice = setting('bill.hide_price', $hidePrice);
                break;
        }

        return $hidePrice;
    }

    protected function getHideDiscount($type, $hideDiscount)
    {
        if (!empty($hideDiscount)) {
            return $hideDiscount;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $hideDiscount = setting('invoice.hide_discount', $hideDiscount);
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $hideDiscount = setting('bill.hide_discount', $hideDiscount);
                break;
        }

        return $hideDiscount;
    }

    protected function getHideAmount($type, $hideAmount)
    {
        if (!empty($hideAmount)) {
            return $hideAmount;
        }

        switch ($type) {
            case 'sale':
            case 'income':
            case 'invoice':
                $hideAmount = setting('invoice.hide_amount', $hideAmount);
                break;
            case 'bill':
            case 'expense':
            case 'purchase':
                $hideAmount = setting('bill.hide_amount', $hideAmount);
                break;
        }

        return $hideAmount;
    }
}
