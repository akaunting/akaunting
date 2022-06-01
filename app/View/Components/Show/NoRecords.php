<?php

namespace App\View\Components\Show;

use App\Abstracts\View\Component;
use Illuminate\Support\Str;

class NoRecords extends Component
{
    /** @var string */
    public $type;

    public $model;

    /** @var string */
    public $page;

    /** @var string */
    public $group;

    /** @var string */
    public $backgroundColor;

    /** @var string */
    public $textColor;

    /** @var string */
    public $image;

    /** @var string */
    public $description;

    /** @var string */
    public $url;

    /** @var string */
    public $textAction;

    public $urls = [
        'account'   => [
            'transactions'  => 'accounts.create-income',
            'transfers'     => 'accounts.create-transfer',
        ],
        'customer'  => [
            'invoices'      => 'customers.create-invoice',
            'transactions'  => 'customers.create-income',
        ],
        'vendor'    => [
            'bills'         => 'vendors.create-bill',
            'transactions'  => 'vendors.create-expense',
        ],
    ];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $type = '', $model = false, string $page = '', string $group = '',
        string $backgroundColor = '', string $textColor = '', 
        string $image = '', string $description = '', string $url = '', string $textAction = ''
    ) {
        $this->type = $type;
        $this->model = $model;
        $this->page = $page;
        $this->group = $group;
        $this->backgroundColor = ! empty($backgroundColor) ? $backgroundColor : 'bg-lilac-900';
        $this->textColor = ! empty($textColor) ? $textColor : 'text-purple';
        $this->image = $this->getImage($image);
        $this->description = $this->getDescription($description);
        $this->url = $this->getUrl($url);
        $this->textAction = $this->getTextAction($textAction);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.show.no-records');
    }

    protected function getImage($image)
    {
        if (! empty($image)) {
            return $image;
        }

        $image = match ($this->type) {
            'account'   => 'public/img/no_records/accounts_' . $this->page . '.png',
            'customer'  => 'public/img/no_records/customers_' . $this->page . '.png',
            'vendor'    => 'public/img/no_records/vendors_' . $this->page . '.png',
            default     => '',
        };

        return $image;
    }

    protected function getDescription($description)
    {
        if (! empty($description)) {
            return $description;
        }

        $prefix = Str::plural($this->type);

        $description = match ($this->type) {
            'account', 'customer', 'vendor' => trans($prefix . '.no_records.' . $this->page),
            default     => '',
        };

        return $description;
    }

    protected function getUrl($url)
    {
        if (! empty($url)) {
            return $url;
        }

        if (array_key_exists($this->type, $this->urls) && array_key_exists($this->page, $this->urls[$this->type])) {
            return route($this->urls[$this->type][$this->page], $this->model->id);
        }

        return $url;
    }

    protected function getTextAction($textAction)
    {
        if (! empty($textAction)) {
            return $textAction;
        }

        $textAction = match ($this->type) {
            'account', 'customer', 'vendor' => trans('general.title.new', ['type' => trans_choice('general.' . $this->page, 1)]),
            default => trans('modules.learn_more'),
        };

        return $textAction;
    }
}
