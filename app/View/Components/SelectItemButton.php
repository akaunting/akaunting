<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Common\Item;

class SelectItemButton extends Component
{
    /** @var string */
    public $type;

    /** @var bool */
    public $isSale;

    /** @var bool */
    public $isPurchase;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $type = 'sale', bool $isSale = true, bool $isPurchase = false)
    {
        $this->type = $type;
        $this->isSale = $isSale;
        $this->isPurchase = $isPurchase;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $items = Item::enabled()->orderBy('name')->take(setting('default.select_limit'))->get();

        foreach ($items as $item) {
            $price = $item->sale_price;

            if ($this->type == 'purchase' || $this->isPurchase) {
                $price = $item->purchase_price;
            }

            $item->price = $price;
        }

        $price = ($this->isPurchase) ? 'purchase_price' : 'sale_price';

        return view('components.select-item-button', compact('items', 'price'));
    }
}
