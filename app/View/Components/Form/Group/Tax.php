<?php

namespace App\View\Components\Form\Group;

use App\Abstracts\View\Components\Form;
use App\Models\Setting\Currency as Model;

class Tax extends Form
{
    public $type = 'tax';

    public $path;

    public $field;

    public $currencies;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        if (empty($this->name)) {
            $this->name = 'currency_code';
        }

        $this->path = route('modals.currencies.create');

        $this->field = [
            'key' => 'code',
            'value' => 'name'
        ];

        $this->currencies = Model::enabled()->orderBy('name')->pluck('name', 'code');

        if (empty($this->selected)) {
            $this->selected = setting('default.currency');
        }

        return view('components.form.group.tax');
    }
}
